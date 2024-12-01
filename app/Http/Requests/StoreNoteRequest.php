<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNoteRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'storage_link' => 'nullable|string',
            'university_id' => 'nullable|exists:universities,id',
            'department_id' => 'nullable|exists:departments,id',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'semester' => 'nullable|in:fall,spring,summer'
        ];
    }
}
