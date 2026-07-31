<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventConfig;
use App\Models\FormOption;
use Illuminate\Http\Request;

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
        $eventConfig->update($this->validate($request));
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
            'max_projects_per_structure' => 'required|integer|min:1',
        ]);

        $data['show_questionnaire'] = $request->boolean('show_questionnaire');

        return $data;
    }
}
