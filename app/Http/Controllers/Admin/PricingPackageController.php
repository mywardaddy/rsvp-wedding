<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePricingPackageRequest;
use App\Http\Requests\UpdatePricingPackageRequest;
use App\Models\PricingPackage;
use App\Models\PricingFeature;
use Illuminate\Http\Request;

class PricingPackageController extends Controller
{
    public function index()
    {
        $packages = PricingPackage::with('features')
            ->ordered()
            ->get();

        return view('admin.pricing.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.pricing.create');
    }

    public function store(StorePricingPackageRequest $request)
    {
        $data = $request->validated();
        $features = $data['features'] ?? [];
        unset($data['features']);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['discount_value'] = $data['discount_type'] === 'none' ? 0 : ($data['discount_value'] ?? 0);

        $package = PricingPackage::create($data);

        // Create features
        foreach ($features as $index => $feature) {
            if (!empty($feature['name'])) {
                $package->features()->create([
                    'name' => $feature['name'],
                    'is_included' => $feature['is_included'] ?? true,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.pricing.index')
            ->with('success', 'Paket "' . $package->name . '" berhasil ditambahkan.');
    }

    public function edit(PricingPackage $package)
    {
        $package->load('features');
        return view('admin.pricing.edit', compact('package'));
    }

    public function update(UpdatePricingPackageRequest $request, PricingPackage $package)
    {
        $data = $request->validated();
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active', true);
        $data['discount_value'] = $data['discount_type'] === 'none' ? 0 : ($data['discount_value'] ?? 0);

        $package->update($data);

        return redirect()->route('admin.pricing.index')
            ->with('success', 'Paket "' . $package->name . '" berhasil diperbarui.');
    }

    public function destroy(PricingPackage $package)
    {
        $name = $package->name;

        if ($package->orders()->exists()) {
            return back()->with('error', 'Paket "' . $name . '" tidak bisa dihapus karena masih memiliki pesanan.');
        }

        $package->delete();

        return redirect()->route('admin.pricing.index')
            ->with('success', 'Paket "' . $name . '" berhasil dihapus.');
    }

    public function toggleActive(PricingPackage $package)
    {
        $package->update(['is_active' => !$package->is_active]);

        $status = $package->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', 'Paket "' . $package->name . '" berhasil ' . $status . '.');
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'packages' => ['required', 'array'],
            'packages.*.id' => ['required', 'exists:pricing_packages,id'],
            'packages.*.sort_order' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($request->packages as $pkg) {
            PricingPackage::where('id', $pkg['id'])->update(['sort_order' => $pkg['sort_order']]);
        }

        return response()->json(['success' => true]);
    }
}
