<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
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
        $rules = [
            'account_id' => 'required|exists:accounts,id',
            'type' => 'required|in:income,expense,transfer',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
        ];

        // Category is required for income and expense, not for transfers
        if (in_array($this->type, ['income', 'expense'])) {
            $rules['category_id'] = 'nullable|exists:categories,id';
        }

        // Destination account is required for transfers
        if ($this->type === 'transfer') {
            $rules['to_account_id'] = 'required|exists:accounts,id|different:account_id';
        }

        return $rules;
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'account_id.required' => 'Please select an account.',
            'account_id.exists' => 'Selected account does not exist.',
            'type.required' => 'Transaction type is required.',
            'type.in' => 'Please select a valid transaction type.',
            'amount.required' => 'Amount is required.',
            'amount.numeric' => 'Amount must be a valid number.',
            'amount.min' => 'Amount must be greater than 0.',
            'description.required' => 'Description is required.',
            'date.required' => 'Transaction date is required.',
            'date.date' => 'Please enter a valid date.',
            'category_id.exists' => 'Selected category does not exist.',
            'to_account_id.required' => 'Destination account is required for transfers.',
            'to_account_id.exists' => 'Selected destination account does not exist.',
            'to_account_id.different' => 'Destination account must be different from source account.',
        ];
    }
}