<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormOption;
use Illuminate\Http\Request;

class FormOptionController extends Controller
{
    public function index(Request $request)
    {
        $group   = $request->get('group', 'scientific_domain');
        $options = FormOption::where('group', $group)->orderBy('sort_order')->get();
        $groups  = FormOption::groups();

        return view('admin.form-options.index', compact('options', 'group', 'groups'));
    }

    public function create()
    {
        $groups = FormOption::groups();
        return view('admin.form-options.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'group'      => 'required|string',
            'label'      => 'required|string|max:255',
            'value'      => 'required|string|max:255|alpha_dash',
            'sort_order' => 'nullable|integer|min:0',
            'is_other'   => 'nullable|boolean',
        ]);

        $data['value'] = strtolower($data['value']);
        $data['is_other'] = $request->boolean('is_other');

        try {
            FormOption::create(array_merge($data, ['is_active' => true]));
            return redirect()->route('admin.form-options.index', ['group' => $data['group']])
                ->with('success', 'Option ajoutée avec succès.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Cette valeur existe déjà pour ce groupe.');
        }
    }

    public function edit(FormOption $formOption)
    {
        $groups = FormOption::groups();
        return view('admin.form-options.edit', compact('formOption', 'groups'));
    }

    public function update(Request $request, FormOption $formOption)
    {
        $data = $request->validate([
            'label'      => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_other'   => 'nullable|boolean',
        ]);

        $data['is_other'] = $request->boolean('is_other');

        $formOption->update($data);

        return redirect()->route('admin.form-options.index', ['group' => $formOption->group])
            ->with('success', 'Option mise à jour.');
    }

    public function destroy(FormOption $formOption)
    {
        $group = $formOption->group;
        $formOption->delete();
        return redirect()->route('admin.form-options.index', ['group' => $group])
            ->with('success', 'Option supprimée.');
    }

    public function toggle(FormOption $formOption)
    {
        $formOption->update(['is_active' => !$formOption->is_active]);
        return back()->with('success', 'Statut mis à jour.');
    }

    public function toggleOther(FormOption $formOption)
    {
        $formOption->update(['is_other' => !$formOption->is_other]);
        return back()->with('success', 'Statut "Autre" mis à jour.');
    }
}
