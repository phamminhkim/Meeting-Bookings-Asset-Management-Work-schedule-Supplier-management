<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractRequest extends FormRequest
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
            'contract_num' => 'required|max:50',
            'sign_date' => 'required|date',
            'a_signer' => 'required|max:50',
            'a_position' => 'required|max:50',
            'b_signer' => 'required|max:50',
            'b_position' => 'required|max:50',
            'vendor_id' => 'required',
            'date_begin' => 'required|date',
            // 'date_end'  =>'exclude_if:has_date_end,false|date',
            // 'date_end'  =>'sometimes|date',
            // 'title' => 'sometimes|max:120',
            'content' => 'sometimes|max:255',
            'amount' => 'required',
            // 'company_id' => 'required',
            'contract_terms' => 'required',
            'contract_terms.*.terms_num' => 'required',
            'contract_terms.*.content' => 'required|max:255',
            'contract_terms.*.term_content' => 'required|max:255',
            'contract_terms.*.note' => 'sometimes|max:100',
            'contract_terms.*.frequency' => 'sometimes|min:0',
            'contract_terms.*.duration' => 'sometimes|min:0',
            'contract_terms.*.date_due' => 'required|date',
            'contract_terms.*.amount' => 'required',
            'attachment_file.*.name' => 'sometimes|required',
            'attachment_file.*.size' => 'sometimes|required',
            'attachment_file.*.base64' => 'sometimes|required',
        ];
    }
    public function messages()
    {
        return [
            'contract_terms.required' => 'Chưa nhập nội dung điều khoản.',
            'contract_terms.*.content.max' => 'Điều khoản: nội dung lớn hơn :max.',
            'contract_terms.*.content.required' => 'Điều khoản: nội dung rỗng.',
            'contract_terms.*.term_content.required' => 'Điều khoản TT: nội dung rỗng.',
            'contract_terms.*.note.required' => 'Điều khoản: ghi chú rỗng.',
            'contract_terms.*.note.max' => 'Điều khoản: ghi chú lớn hơn :max.',
            'contract_terms.*.date_due.required' => 'Điều khoản: ngày đến hạn rỗng',
            'contract_terms.*.date_due.date' => 'Điều khoản: ngày đến hạn không hợp lệ',
            'contract_terms.*.amount.date' => 'Điều khoản: số tiền rỗng',

        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $this->validator = $validator;
    }

}
