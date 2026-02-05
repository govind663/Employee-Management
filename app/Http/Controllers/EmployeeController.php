<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\State;
use App\Models\City;
use App\Services\EmployeeDocumentService;
use App\Http\Requests\EmployeeGeneralRequest;
use App\Http\Requests\EmployeeDocumentRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class EmployeeController extends Controller
{
    public function __construct(
        protected EmployeeDocumentService $documentService
    ) {}

    /* ================= LIST ================= */
    public function index()
    {
        if (request()->ajax()) {
            $employees = Employee::with(['state','city','documents'])
                ->orderByDesc('employee_id')
                ->get();

            // Decode department for frontend
            foreach($employees as $emp){
                $emp->department = is_string($emp->department) ? json_decode($emp->department, true) ?? [] : $emp->department;
            }

            return response()->json(['data' => $employees]);
        }

        return view('employees.index', [
            'states' => State::all()
        ]);
    }

    /* ================= STORE ================= */
    public function store(EmployeeGeneralRequest $generalRequest)
    {
        $documentRequest = app(EmployeeDocumentRequest::class);
        $documentRequest->merge($generalRequest->all());
        $documentRequest->validateResolved();

        DB::beginTransaction();

        try {

            $data = $generalRequest->validated();

            $employee = Employee::create($data);

            $this->documentService->storeDocuments($employee, $documentRequest);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Employee added successfully'
            ]);

        } catch (Throwable $e) {

            DB::rollBack();

            Log::error('Employee Store Failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while saving employee'
            ], 500);
        }
    }

    /* ================= EDIT ================= */
    public function edit($id)
    {
        $employee = Employee::with('documents')->findOrFail($id);

        return response()->json([
            'employee_id' => $employee->employee_id,
            'name' => $employee->name,
            'email_id' => $employee->email_id,
            'mobile_no' => $employee->mobile_no,
            'designation' => $employee->designation,
            'address' => $employee->address,
            'gender' => $employee->gender,
            'department' => $employee->department ?? [],
            'state_id' => $employee->state_id,
            'city_id' => $employee->city_id,
            'documents' => $employee->documents,
        ]);
    }

    /* ================= UPDATE ================= */
    public function update(
        EmployeeGeneralRequest $generalRequest,
        EmployeeDocumentRequest $documentRequest,
        $id
    ) {
        DB::beginTransaction();

        try {

            $employee = Employee::with('documents')->findOrFail($id);

            // General data update
            $employee->update($generalRequest->validated());

            // Documents sync
            $this->documentService->syncDocuments($employee, $documentRequest);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Employee updated successfully'
            ]);

        } catch (Throwable $e) {

            DB::rollBack();

            Log::error('Employee Update Failed', [
                'employee_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while updating employee'
            ], 500);
        }
    }

    /* ================= DELETE ================= */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $employee = Employee::with('documents')->findOrFail($id);

            $this->documentService->deleteAllDocuments($employee);

            $employee->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Employee deleted successfully'
            ]);

        } catch (Throwable $e) {
            DB::rollBack();

            Log::error('Employee Delete Failed', [
                'employee_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to delete employee'
            ], 500);
        }
    }

    /* ================= GET CITIES ================= */
    public function getCities($state_id)
    {
        return City::where('state_id', $state_id)->get();
    }
}
