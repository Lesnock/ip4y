<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
            'title' => 'required|min:1',
            'description' => 'required|min:1',
            'due_date' => 'required',
            'status' => 'required|in:pendent,in-progress,completed',
            'project_id' => 'required|integer',
            'responsible_id' => 'required|integer',
        ];
    }
}
