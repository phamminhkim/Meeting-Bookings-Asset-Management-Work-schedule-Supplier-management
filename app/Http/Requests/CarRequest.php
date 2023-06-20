<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
{
    public $validator = null;
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
     * @return array
     */
    public function rules()
    {
        return [
            'issue_count' => 'required',
            'issue_date' => 'required|date',
            'company_id' => 'required',
            'department_id' => 'required',
            'content' => 'sometimes|max:255',
            'attachment_file.*.name' => 'sometimes|required',
            'attachment_file.*.size' => 'sometimes|required',
            'attachment_file.*.base64' => 'sometimes|required',
        ];
    }
    public function messages()
    {
        return [
            'issue_count.required' => 'Chưa nhập số lần tái diễn.',
            'issue_date.required' => 'Chưa chọn ngày.',
            'company_id.required' => 'Chưa chọn công ty.',
            'department_id.required' => 'Chưa chọn phòng ban.',
            'content.required' => 'Chưa nhập nội dung.'
        ];
    }
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $this->validator = $validator;
    }
}
