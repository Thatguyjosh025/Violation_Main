<?php

namespace App\Http\Controllers;

use App\Models\audits;
use App\Models\sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'header' => 'required|string|max:55',
            'description' => 'required|string|max:555',
        ]);

        sections::create($request->only('header', 'description'));

        // AUDIT LOGGING
        $userId = Auth::user()->id;
        $fieldsToAudit = [
            'header' => $request->header,
            'description' => $request->description,
        ];

        // List of fields to ignore in audit logging (if any)
        $ignoredFields = ['_token', '_method'];

        foreach ($fieldsToAudit as $field => $value) {
            // Skip ignored fields
            if (in_array($field, $ignoredFields)) {
                continue;
            }

            audits::create([
                'changed_at'     => now()->format('Y-m-d H:i'),
                'changed_by'     => $userId,
                'event_type'     => 'Create',
                'field_name'     => $field,
                'old_value'      => null, // No old value for create
                'old_value_text' => null,
                'new_value'      => $value,
                'new_value_text' => $value, // For string fields, the text is the same as the value
            ]);
        }


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
            'header' => 'required|string|max:55',
            'description' => 'required|string|max:555',
        ]);

        $section = sections::findOrFail($id);

        // Store old values
        $oldValues = [
            'header' => $section->header,
            'description' => $section->description,
        ];

        $section->update($request->only('header', 'description'));


        // AUDIT LOGGING
        $userId = Auth::user()->id;
        $fieldsToAudit = [
            'header' => $request->header,
            'description' => $request->description,
        ];

        // List of fields to ignore in audit logging (if any)
        $ignoredFields = ['_token', '_method'];

        foreach ($fieldsToAudit as $field => $newValue) {
            // Skip ignored fields
            if (in_array($field, $ignoredFields)) {
                continue;
            }

            $oldValue = $oldValues[$field] ?? null;

            audits::create([
                'changed_at'     => now()->format('Y-m-d H:i'),
                'changed_by'     => $userId,
                'event_type'     => 'Update',
                'field_name'     => $field,
                'old_value'      => $oldValue,
                'old_value_text' => $oldValue, // For string fields, the text is the same as the value
                'new_value'      => $newValue,
                'new_value_text' => $newValue, // For string fields, the text is the same as the value
            ]);
        }

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
