<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'tgl_lahir' => ['nullable', 'date', 'before:today'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore(Auth::id())
            ],
            'no_HP' => ['nullable', 'string', 'max:20', 'regex:/^[\d\+\-\(\)\s]+$/'],
            'gol_darah' => ['nullable', 'string', 'max:3', 'in:A,B,AB,O'],
            'facebook' => ['nullable', 'string', 'max:255', 'url'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'filename' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5120'], // max 5MB
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama harus diisi.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'tgl_lahir.date' => 'Format tanggal lahir tidak valid.',
            'tgl_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'alamat.max' => 'Alamat tidak boleh lebih dari 500 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh pengguna lain.',
            'no_HP.regex' => 'Format nomor HP tidak valid.',
            'gol_darah.in' => 'Golongan darah harus A, B, AB, atau O.',
            'facebook.url' => 'URL Facebook tidak valid.',
            'filename.image' => 'File harus berupa gambar.',
            'filename.mimes' => 'File harus berformat jpeg, png, atau jpg.',
            'filename.max' => 'Ukuran file tidak boleh lebih dari 5MB.',
        ];
    }
}
