<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentBlock;
use App\Models\EventConfig;
use Illuminate\Http\Request;

class ContentBlockController extends Controller
{
    public function index(Request $request)
    {
        $key    = $request->get('key', array_key_first(ContentBlock::keys()));
        $keys   = ContentBlock::keys();
        $events = EventConfig::orderByDesc('is_active')->orderByDesc('event_start_date')->get();

        $eventId = $request->filled('event_config_id') ? (int) $request->get('event_config_id') : null;

        $block = ContentBlock::where('key', $key)->where('event_config_id', $eventId)->first();

        return view('admin.content-blocks.index', compact('key', 'keys', 'events', 'eventId', 'block'));
    }

    public function edit(Request $request)
    {
        $key   = $request->get('key');
        $keys  = ContentBlock::keys();
        if (!isset($keys[$key])) {
            abort(404);
        }

        $eventId = $request->filled('event_config_id') ? (int) $request->get('event_config_id') : null;
        $events  = EventConfig::orderByDesc('is_active')->orderByDesc('event_start_date')->get();

        $block = ContentBlock::where('key', $key)->where('event_config_id', $eventId)->first();

        return view('admin.content-blocks.edit', compact('key', 'keys', 'block', 'eventId', 'events'));
    }

    public function update(Request $request)
    {
        $key = $request->input('key');
        $keys = ContentBlock::keys();
        if (!isset($keys[$key])) {
            abort(404);
        }

        $type = $keys[$key]['type'];
        $eventId = $request->filled('event_config_id') ? (int) $request->input('event_config_id') : null;

        $data = ['type' => $type, 'is_active' => true];

        if ($type === 'list') {
            $data['content_json'] = $this->parseItems($request->input('items', []));
            $data['content'] = null;
        } else {
            $request->validate(['content' => 'nullable|string|max:5000']);
            $data['content'] = $request->input('content');
            $data['content_json'] = null;
        }

        ContentBlock::updateOrCreate(
            ['key' => $key, 'event_config_id' => $eventId],
            $data
        );

        return redirect()->route('admin.content-blocks.index', ['key' => $key, 'event_config_id' => $eventId])
            ->with('success', 'Contenu mis à jour.');
    }

    public function destroy(Request $request)
    {
        $key = $request->input('key');
        $eventId = $request->filled('event_config_id') ? (int) $request->input('event_config_id') : null;

        ContentBlock::where('key', $key)->where('event_config_id', $eventId)->delete();

        return redirect()->route('admin.content-blocks.index', ['key' => $key, 'event_config_id' => $eventId])
            ->with('success', 'Contenu réinitialisé (retour au texte par défaut).');
    }

    // ─── Sections libres (type CMS, propres à un événement) ──────────────────────

    public function sections(Request $request)
    {
        $eventId = (int) $request->get('event_config_id');
        $event = EventConfig::findOrFail($eventId);
        $events = EventConfig::orderByDesc('is_active')->orderByDesc('event_start_date')->get();
        $sections = ContentBlock::where('event_config_id', $eventId)->whereNull('key')->orderBy('sort_order')->get();

        return view('admin.content-blocks.sections.index', compact('event', 'events', 'sections'));
    }

    public function createSection(Request $request)
    {
        $event = EventConfig::findOrFail((int) $request->get('event_config_id'));
        return view('admin.content-blocks.sections.create', compact('event'));
    }

    public function storeSection(Request $request)
    {
        $data = $request->validate([
            'event_config_id' => 'required|exists:event_configs,id',
            'title'           => 'required|string|max:255',
            'type'            => 'required|in:richtext,list',
            'content'         => 'nullable|string|max:5000',
            'image'           => 'nullable|image|max:4096',
            'remove_image'    => 'nullable|boolean',
        ]);

        $maxOrder = ContentBlock::where('event_config_id', $data['event_config_id'])->whereNull('key')->max('sort_order');

        ContentBlock::create([
            'event_config_id' => $data['event_config_id'],
            'key'             => null,
            'title'           => $data['title'],
            'type'            => $data['type'],
            'content'         => $data['type'] === 'richtext' ? $data['content'] : null,
            'content_json'    => $data['type'] === 'list' ? $this->parseItems($request->input('items', [])) : null,
            'image_path'      => $request->hasFile('image') ? $request->file('image')->store('sections', 'public') : null,
            'sort_order'      => ($maxOrder ?? 0) + 1,
            'is_active'       => true,
        ]);

        return redirect()->route('admin.content-blocks.sections', ['event_config_id' => $data['event_config_id']])
            ->with('success', 'Section ajoutée.');
    }

    public function editSection(ContentBlock $section)
    {
        abort_if($section->key !== null, 404);
        return view('admin.content-blocks.sections.edit', compact('section'));
    }

    public function updateSection(Request $request, ContentBlock $section)
    {
        abort_if($section->key !== null, 404);

        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'type'         => 'required|in:richtext,list',
            'content'      => 'nullable|string|max:5000',
            'image'        => 'nullable|image|max:4096',
            'remove_image' => 'nullable|boolean',
        ]);

        $imagePath = $section->image_path;
        if ($request->hasFile('image')) {
            if ($imagePath) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('sections', 'public');
        } elseif ($request->boolean('remove_image') && $imagePath) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($imagePath);
            $imagePath = null;
        }

        $section->update([
            'title'        => $data['title'],
            'type'         => $data['type'],
            'content'      => $data['type'] === 'richtext' ? $data['content'] : null,
            'content_json' => $data['type'] === 'list' ? $this->parseItems($request->input('items', [])) : null,
            'image_path'   => $imagePath,
        ]);

        return redirect()->route('admin.content-blocks.sections', ['event_config_id' => $section->event_config_id])
            ->with('success', 'Section mise à jour.');
    }

    public function destroySection(ContentBlock $section)
    {
        abort_if($section->key !== null, 404);
        $eventId = $section->event_config_id;
        if ($section->image_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($section->image_path);
        }
        $section->delete();

        return redirect()->route('admin.content-blocks.sections', ['event_config_id' => $eventId])
            ->with('success', 'Section supprimée.');
    }

    public function toggleSection(ContentBlock $section)
    {
        abort_if($section->key !== null, 404);
        $section->update(['is_active' => !$section->is_active]);

        return back()->with('success', 'Statut mis à jour.');
    }

    public function reorderSections(Request $request)
    {
        $data = $request->validate([
            'event_config_id' => 'required|exists:event_configs,id',
            'ids'             => 'required|array',
            'ids.*'           => 'integer|exists:content_blocks,id',
        ]);

        foreach ($data['ids'] as $position => $id) {
            ContentBlock::where('id', $id)
                ->where('event_config_id', $data['event_config_id'])
                ->whereNull('key')
                ->update(['sort_order' => $position + 1]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Un item de liste n'a pas forcément de titre (ex : simples puces) — on ne garde
     * que ceux ayant au moins un titre OU une description non vide.
     */
    private function parseItems(array $items): array
    {
        return collect($items)
            ->filter(fn ($i) => !empty(trim($i['title'] ?? '')) || !empty(trim($i['description'] ?? '')))
            ->map(fn ($i) => ['title' => trim($i['title'] ?? ''), 'description' => trim($i['description'] ?? '')])
            ->values()
            ->all();
    }
}
