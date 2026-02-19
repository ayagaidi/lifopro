<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\CardFieldVisibility;
use Illuminate\Http\Request;

class CardFieldVisibilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('card-field-visibility-list');
        $fields = CardFieldVisibility::getAllFields();
        return view('dashbord.card_field_visibility.index', compact('fields'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->authorize('card-field-visibility-list');
        $field = CardFieldVisibility::findOrFail($id);
        $field->visible = $request->input('visible', false);
        $field->save();

        return redirect()->route('dashbord.card_field_visibility.index')
            ->with('success', 'تم تحديث إعدادات العرض بنجاح');
    }

    /**
     * Update all fields visibility settings
     */
    public function updateAll(Request $request)
    {
        $this->authorize('card-field-visibility-list');
        $visibilities = $request->input('visibilities', []);
        
        foreach ($visibilities as $id => $visible) {
            CardFieldVisibility::where('id', $id)
                ->update(['visible' => (bool)$visible]);
        }

        return redirect()->route('dashbord.card_field_visibility.index')
            ->with('success', 'تم تحديث جميع إعدادات العرض بنجاح');
    }

    /**
     * Reorder fields
     */
    public function reorder(Request $request)
    {
        $this->authorize('card-field-visibility-list');
        $orders = $request->input('orders', []);
        
        foreach ($orders as $id => $order) {
            CardFieldVisibility::where('id', $id)
                ->update(['order' => $order]);
        }

        return response()->json(['success' => true]);
    }
}
