<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActionRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'action_code' => ['required', 'string', 'max:30', Rule::unique('actions', 'action_code')->ignore($this->route('action'))],
            'reference' => ['required', 'string', 'max:100'],
            'theme' => ['required', 'string', 'max:150'],
            'required_action' => ['required', 'string'],
            'owner' => ['required', 'string', 'max:255'],
            'target' => ['nullable', 'string', 'max:255'],
            'due_date' => ['nullable', 'date'],
            'status' => ['required', 'in:Selesai,Belum disahkan,Dalam tindakan,Pemantauan berterusan,Menunggu pihak luar'],
            'priority' => ['required', 'in:Tinggi,Sederhana,Rendah'],
            'basis' => ['required', 'string', 'max:100'],
            'progress' => ['nullable', 'string'],
            'evidence' => ['nullable', 'string'],
            'updated_on' => ['nullable', 'date'],
            'source_note' => ['nullable', 'string'],
            'meeting_date' => ['nullable', 'date'],
        ];
    }
}
