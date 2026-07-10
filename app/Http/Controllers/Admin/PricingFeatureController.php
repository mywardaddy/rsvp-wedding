<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PricingFeature;
use App\Models\PricingPackage;
use Illuminate\Http\Request;

class PricingFeatureController extends Controller
{
    public function store(Request $request, PricingPackage $package)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_included' => ['boolean'],
        ]);

        $maxOrder = $package->features()->max('sort_order') ?? -1;

        $package->features()->create([
            'name' => $request->name,
            'is_included' => $request->boolean('is_included', true),
            'sort_order' => $maxOrder + 1,
        ]);

        return back()->with('success', 'Fitur berhasil ditambahkan.');
    }

    public function update(Request $request, PricingFeature $feature)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'is_included' => ['boolean'],
        ]);

        $feature->update([
            'name' => $request->name,
            'is_included' => $request->boolean('is_included', true),
        ]);

        return back()->with('success', 'Fitur berhasil diperbarui.');
    }

    public function destroy(PricingFeature $feature)
    {
        $feature->delete();

        return back()->with('success', 'Fitur berhasil dihapus.');
    }

    public function updateOrder(Request $request, PricingPackage $package)
    {
        $request->validate([
            'features' => ['required', 'array'],
            'features.*.id' => ['required', 'exists:pricing_features,id'],
            'features.*.sort_order' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($request->features as $feat) {
            PricingFeature::where('id', $feat['id'])
                ->where('pricing_package_id', $package->id)
                ->update(['sort_order' => $feat['sort_order']]);
        }

        return response()->json(['success' => true]);
    }
}
