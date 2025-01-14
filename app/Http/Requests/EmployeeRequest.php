<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmployeeRequest extends FormRequest
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
    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'division_id' => 'required|string',
            'position' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        if ($this->isMethod('post')) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['image'] = 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }
        
        return $rules;
    }


    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        throw new HttpResponseException(response()->json($errors, 422));
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'image.required' => 'Image is required',
            'phone.required' => 'Phone is required',
            'division_id.required' => 'Division is required',
            'position.required' => 'Position is required'
        ];
    }
}
