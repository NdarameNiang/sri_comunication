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
            $items = collect($request->input('items', []))
                ->filter(fn ($i) => !empty(trim($i['title'] ?? '')))
                ->map(fn ($i) => ['title' => trim($i['title']), 'description' => trim($i['description'] ?? '')])
                ->values()
                ->all();
            $data['content_json'] = $items;
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
}
