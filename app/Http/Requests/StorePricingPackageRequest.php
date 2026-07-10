<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePricingPackageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isSuperadmin();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:pricing_packages,slug'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'integer', 'min:0'],
            'discount_type' => ['required', 'in:none,percentage,fixed'],
            'discount_value' => ['required_unless:discount_type,none', 'integer', 'min:0'],
            'badge' => ['nullable', 'string', 'max:100'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
            'sort_order' => ['integer', 'min:0'],
            'features' => ['nullable', 'array'],
            'features.*.name' => ['required_with:features', 'string', 'max:255'],
            'features.*.is_included' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama paket wajib diisi.',
            'price.required' => 'Harga paket wajib diisi.',
            'price.min' => 'Harga paket tidak boleh negatif.',
            'discount_value.required_unless' => 'Nilai diskon wajib diisi jika tipe diskon dipilih.',
        ];
    }
}
