<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'document_type'   => 'required|array|min:1',
            'document_type.*' => 'required|string',

            'document_file'   => 'nullable|array',
            'existing_document_file' => 'nullable|array',
        ];

        // Loop through each document row
        foreach ($this->input('document_type', []) as $index => $type) {
            $existing = $this->input("existing_document_file.$index");

            // File required only if existing file not present
            $rules["document_file.$index"] = $existing
                ? 'nullable|file|max:5120|mimes:xls,xlsx,pdf,doc,docx'
                : 'required|file|max:5120|mimes:xls,xlsx,pdf,doc,docx';
        }

        return $rules;
    }

    public function messages(): array
    {
        $messages = [
            'document_type.required'   => 'Please add at least one document.',
            'document_type.*.required' => 'Document type is required.',
            'document_file.*.required' => 'Document file is required.',
            'document_file.*.mimes'    => 'Invalid file type. Only Excel, PDF, and Word allowed.',
            'document_file.*.max'      => 'File size must not exceed 5MB.',
        ];

        return $messages;
    }
}
