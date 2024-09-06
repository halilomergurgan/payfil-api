<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessPaymentRequest extends FormRequest
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
        return [
            'fullName' => 'required|string|max:255',
            'cardNumber' => 'required|digits:16',
            'expiryDate' => 'required|date_format:m/y|after:today',
            'cvv' => 'required|digits_between:3,4',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|in:USD,EUR,TRY',
        ];
    }

    public function messages()
    {
        return [
            'fullName.required' => 'İsim Soyisim gereklidir.',
            'cardNumber.required' => 'Kart numarası gereklidir.',
            'cardNumber.digits' => 'Kart numarası 16 haneli olmalıdır.',
            'expiryDate.required' => 'Son kullanma tarihi gereklidir.',
            'expiryDate.date_format' => 'Son kullanma tarihi MM/YY formatında olmalıdır.',
            'expiryDate.after' => 'Son kullanma tarihi geçersizdir.',
            'cvv.required' => 'CVV gereklidir.',
            'cvv.digits_between' => 'CVV kodu 3 veya 4 haneli olmalıdır.',
            'amount.required' => 'Tutar gereklidir.',
            'amount.min' => 'Tutar pozitif olmalıdır.',
            'currency.required' => 'Para birimi gereklidir.',
            'currency.in' => 'Geçerli bir para birimi seçilmelidir (USD, EUR, TRY).',
        ];
    }
}
