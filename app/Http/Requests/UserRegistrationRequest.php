<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Informasi Umum
            'bib_name' => 'required|string|max:8',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:participants,email',
            'phone' => 'required|string|max:20',
            'community' => 'nullable|string|max:255',

            // Biodata
            'gender' => 'required|in:female,male',
            // 'nik' => 'required|string|size:16|unique:participants,nik',
            'birthplace' => 'required|string|max:255',
            'birthdate' => 'required|date|before_or_equal:' . now()->subYears(13)->toDateString(),
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'jersey_size' => 'nullable',

            // Informasi Kesehatan
            'blood_type' => 'required|string|max:3',
            'medical_history' => 'nullable|string|max:255',
            'medical_note' => 'nullable|string|max:500',

            'accept_promo' => 'nullable',

            'ticket_id' => 'required|integer'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // 'email.unique' => 'Email ini sudah terdaftar',
            'email.required' => 'Kolom email harus diisi',
            'bib_name.required' => 'Nama BIB harus diisi',
            'full_name.required' => 'Nama Lengkap harus diisi',
            'phone.required' => 'Nomor telepon harus diisi',
            'gender.required' => 'Jenis kelamin harus diisi',
            // 'nik.required' => 'NIK harus diisi',
            // 'nik.unique' => 'NIK sudah terdaftar',
            'birthplace.required' => 'Tempat lahir harus diisi',
            'birthdate.required' => 'Tanggal lahir harus diisi',
            'address.required' => 'Alamat harus diisi',
            'city.required' => 'Kota harus diisi',
            'blood_type.required' => 'Golongan darah harus diisi',
            'birthdate.before_or_equal' => 'Usia minimal harus 13 tahun.'
        ];
    }
}
