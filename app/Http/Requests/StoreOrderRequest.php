<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Public access (guest checkout)
    }

    public function rules(): array
    {
        return [
            'pricing_package_id' => ['required', 'exists:pricing_packages,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_whatsapp' => ['required', 'string', 'max:20'],
            'groom_name' => ['required', 'string', 'max:255'],
            'bride_name' => ['required', 'string', 'max:255'],
            'wedding_date' => ['required', 'date', 'after:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'pricing_package_id.required' => 'Paket harus dipilih.',
            'pricing_package_id.exists' => 'Paket yang dipilih tidak valid.',
            'customer_name.required' => 'Nama lengkap wajib diisi.',
            'customer_email.required' => 'Email wajib diisi.',
            'customer_email.email' => 'Format email tidak valid.',
            'customer_whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'groom_name.required' => 'Nama pengantin pria wajib diisi.',
            'bride_name.required' => 'Nama pengantin wanita wajib diisi.',
            'wedding_date.required' => 'Tanggal pernikahan wajib diisi.',
            'wedding_date.after' => 'Tanggal pernikahan harus di masa depan.',
        ];
    }
}
