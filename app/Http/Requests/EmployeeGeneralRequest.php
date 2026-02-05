<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeGeneralRequest extends FormRequest
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
        $employeeId = $this->employee_id ?? null;

        return [
            'name'        => 'required|string|max:255',
            'email_id'    => 'required|email|unique:employees,email_id,' . $employeeId . ',employee_id',
            'mobile_no'   => 'required|digits_between:10,15|unique:employees,mobile_no,' . $employeeId . ',employee_id',
            'gender'      => 'required|in:Male,Female',
            'designation' => 'required|string|max:255',
            'department'  => 'required|array',
            'address'     => 'required|string',
            'state_id'    => 'required|exists:states,id',
            'city_id'     => 'required|exists:cities,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Employee name is required.',
            'name.string' => 'Employee name must be a string.',
            'name.max' => 'Employee name must not exceed 255 characters.',

            'email_id.required' => 'Email Id is required.',
            'email_id.unique' => 'The email has already been taken.',
            'email_id.email' => 'Email Id must be a valid email address.',

            'mobile_no.required' => 'Mobile number is required.',
            'mobile_no.digits_between' => 'Mobile number must be between 10 and 15 digits.',

            'gender.required' => 'Please select gender.',
            'gender.in' => 'Please select a valid gender.',

            'designation.required' => 'Designation is required.',
            'designation.string' => 'Designation must be a string.',
            'designation.max' => 'Designation must not exceed 255 characters.',

            'department.required' => 'Select at least one department.',

            'address.required' => 'Address is required.',

            'state_id.required' => 'Please select state.',
            'state_id.exists' => 'Selected state does not exist.',

            'city_id.required' => 'Please select city.',    
            'city_id.exists' => 'Selected city does not exist.',
        ];
    }
}
