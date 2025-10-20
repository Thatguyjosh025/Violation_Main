<?php

namespace App\Http\Controllers;

use App\Models\sections;
use Illuminate\Http\Request;

class HandbookController extends Controller
{
    //
    public function violation_handbook()
    {
        $sections = sections::orderBy('created_at')->get();
        return view('components.shared.Handbook', compact('sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'header' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        sections::create($request->only('header', 'description'));
        return redirect()->back()->with('success', 'Section added!');
    }
    public function refresh()
    {
        $sections = sections::orderBy('created_at')->get();
        return view('components.shared.partials.handbook-sections', compact('sections'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'header' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $section = sections::findOrFail($id);
        $section->update($request->only('header', 'description'));

        return response()->json(['success' => true]);
    }

    public function sectionHtml($id)
    {
        $section = sections::findOrFail($id);
        return view('components.shared.partials.single-section', compact('section'));
    }
    public function destroy($id)
    {
        $section = sections::findOrFail($id);
        $section->delete();

        return response()->json(['success' => true]);
    }
}
