<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventConfig;
use App\Models\FormOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventConfigController extends Controller
{
    public function index()
    {
        $configs = EventConfig::latest()->paginate(10);
        return view('admin.event-configs.index', compact('configs'));
    }

    public function create()
    {
        $audienceCategories = FormOption::forGroup('population_category');
        return view('admin.event-configs.create', compact('audienceCategories'));
    }

    public function store(Request $request)
    {
        $data = $this->validate($request);
        $data['is_active'] = false;
        $data = array_merge($data, $this->handleBranding($request));
        $eventConfig = EventConfig::create($data);
        $eventConfig->audienceCategories()->sync($request->input('audience_category_ids', []));
        return redirect()->route('admin.event-configs.index')->with('success', 'Événement créé avec succès.');
    }

    public function show(EventConfig $eventConfig)
    {
        return redirect()->route('admin.event-configs.edit', $eventConfig);
    }

    public function edit(EventConfig $eventConfig)
    {
        $audienceCategories = FormOption::forGroup('population_category');
        $eventConfig->load('audienceCategories');
        return view('admin.event-configs.edit', compact('eventConfig', 'audienceCategories'));
    }

    public function update(Request $request, EventConfig $eventConfig)
    {
        $data = array_merge($this->validate($request), $this->handleBranding($request, $eventConfig));
        $eventConfig->update($data);
        $eventConfig->audienceCategories()->sync($request->input('audience_category_ids', []));
        return redirect()->route('admin.event-configs.index')->with('success', 'Événement mis à jour.');
    }

    public function destroy(EventConfig $eventConfig)
    {
        $eventConfig->delete();
        return redirect()->route('admin.event-configs.index')->with('success', 'Événement supprimé.');
    }

    public function activate(EventConfig $eventConfig)
    {
        EventConfig::where('is_active', true)->update(['is_active' => false]);
        $eventConfig->update(['is_active' => true]);
        return back()->with('success', "« {$eventConfig->event_name} » est maintenant l'événement actif.");
    }

    private function validate(Request $request): array
    {
        $data = $request->validate([
            'event_name'          => 'required|string|max:255',
            'event_slug'          => 'required|string|max:100|alpha_dash',
            'event_description'   => 'nullable|string',
            'organizer'           => 'nullable|string|max:255',
            'event_start_date'    => 'nullable|date',
            'event_end_date'      => 'nullable|date|after_or_equal:event_start_date',
            'submission_open_at'   => 'nullable|date',
            'submission_close_at'  => 'nullable|date|after_or_equal:submission_open_at',
            'inscription_open_at'  => 'nullable|date',
            'inscription_close_at' => 'nullable|date|after_or_equal:inscription_open_at',
            'show_questionnaire'   => 'nullable|boolean',
            'allow_public_submission' => 'nullable|boolean',
            'max_projects_per_structure' => 'required|integer|min:1',
            'primary_color'       => 'nullable|regex:/^#[0-9a-fA-F]{6}$/',
        ]);

        $data['show_questionnaire']      = $request->boolean('show_questionnaire');
        $data['allow_public_submission'] = $request->boolean('allow_public_submission');

        return $data;
    }

    /**
     * Upload des 3 images de branding (logo, logo blanc, image de fond) — même pattern que
     * Admin\ContentBlockController (store('...', 'public') + suppression de l'ancien fichier
     * au remplacement). Chaque image nullable : si non fournie et sans case "supprimer" cochée,
     * la valeur existante est conservée (update) ou laissée vide (create).
     */
    private function handleBranding(Request $request, ?EventConfig $eventConfig = null): array
    {
        $branding = [];

        foreach (['logo' => 'logo_path', 'logo_white' => 'logo_white_path', 'hero_image' => 'hero_image_path'] as $field => $column) {
            $current = $eventConfig?->$column;

            if ($request->hasFile($field)) {
                if ($current) {
                    Storage::disk('public')->delete($current);
                }
                $branding[$column] = $request->file($field)->store('event-branding', 'public');
            } elseif ($request->boolean("remove_{$field}") && $current) {
                Storage::disk('public')->delete($current);
                $branding[$column] = null;
            }
        }

        return $branding;
    }
}
