<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class ValidDocumentFile implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Ensure file instance
        if (!($value instanceof UploadedFile)) {
            $fail('The uploaded file is invalid.');
            return;
        }

        // Allowed extensions as per document
        $allowedExtensions = ['pdf', 'xls', 'xlsx', 'doc', 'docx'];

        $extension = strtolower($value->getClientOriginalExtension());

        if (!in_array($extension, $allowedExtensions)) {
            $fail('Only Excel, PDF and Word documents are allowed.');
        }
    }
}
