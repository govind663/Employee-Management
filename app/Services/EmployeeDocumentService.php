<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\EmployeeDocument;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class EmployeeDocumentService
{
    /* ================= STORE DOCUMENTS ================= */
    public function storeDocuments(Employee $employee, Request $request): void
    {
        $this->validateDocuments($request);

        foreach ($request->file('document_file', []) as $index => $file) {

            $type = $request->document_type[$index] ?? 'other';
            $path = $file->store("employee_documents/$type", 'public');

            EmployeeDocument::create([
                'employee_id'   => $employee->employee_id,
                'document_type' => $type,
                'document_file' => $path,
            ]);
        }
    }

    /* ================= SYNC / UPDATE DOCUMENTS ================= */
    public function syncDocuments(Employee $employee, Request $request): void
    {
        $this->validateDocuments($request);

        $types    = $request->document_type ?? [];
        $files    = $request->file('document_file') ?? [];
        $existing = $request->existing_document_file ?? [];

        $keptIds = [];

        foreach ($types as $index => $type) {

            $docId = $existing[$index] ?? null;
            $file  = $files[$index] ?? null;

            /* ================= UPDATE EXISTING ================= */
            if ($docId) {

                $doc = $employee->documents()
                    ->where('id', $docId)
                    ->first();

                if ($doc) {

                    $doc->document_type = $type;

                    if ($file) {

                        if ($doc->document_file && Storage::disk('public')->exists($doc->document_file)) {
                            Storage::disk('public')->delete($doc->document_file);
                        }

                        $path = $file->store("employee_documents/$type", 'public');
                        $doc->document_file = $path;
                    }

                    $doc->save();

                    // $keptIds[] = $doc->id; TODO
                }
            }

            /* ================= CREATE NEW ================= */
            else {

                if ($file) {

                    $path = $file->store("employee_documents/$type", 'public');

                    $newDoc = $employee->documents()->create([
                        'document_type' => $type,
                        'document_file' => $path,
                    ]);

                    // $keptIds[] = $newDoc->id; TODO
                }
            }
        }

        /* ================= DELETE REMOVED (Not Required) ================= */
        // $employee->documents()
        //     // ->whereNotIn('id', $keptIds) TODO
        //     ->get()
        //     ->each(function ($doc) {

        //         if ($doc->document_file && Storage::disk('public')->exists($doc->document_file)) {
        //             Storage::disk('public')->delete($doc->document_file);
        //         }

        //         $doc->delete();
        //     });
    }

    /* ================= DELETE ALL DOCUMENTS ================= */
    public function deleteAllDocuments(Employee $employee): void
    {
        foreach ($employee->documents ?? [] as $doc) {

            if ($doc->document_file && Storage::disk('public')->exists($doc->document_file)) {
                Storage::disk('public')->delete($doc->document_file);
            }

            $doc->delete();
        }
    }

    /* ================= DOCUMENT VALIDATION ================= */
    private function validateDocuments(Request $request): void
    {
        $rules = [];
        $messages = [];

        foreach ($request->document_type ?? [] as $index => $type) {

            $fileKey     = "document_file.$index";
            $existingKey = "existing_document_file.$index";

            $rules[$fileKey] = [
                $request->input($existingKey) ? 'nullable' : 'required',
                'file',
                'max:5120',
                'mimes:xls,xlsx,pdf,doc,docx',
            ];

            $messages[$fileKey.'.mimes']    = "Invalid file type. Only Excel, PDF, and Word documents are allowed.";
            $messages[$fileKey.'.max']      = "File size must not exceed 5MB.";
            $messages[$fileKey.'.required'] = "Please upload a file for $type.";
        }

        if (!empty($rules)) {

            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        }
    }
}
