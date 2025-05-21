<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CsvRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'csv_file' => 'required|file|mimes:csv',
        ];
    }

    public function messages()
    {
        return [
            'csv_file.required' => 'The CSV file is required.',
            'csv_file.file' => 'The uploaded file must be a valid CSV file.',
            'csv_file.mimes' => 'The file must be a CSV file.',
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = response()->json([
            'success' => false,
            'message' => $validator->errors()->all(),
        ], 422);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
