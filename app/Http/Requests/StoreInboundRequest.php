<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreInboundRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->Has_Permissions('create_inbound');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rubrique' => 'required|integer|exists:rubriques,id',
            'commande_note_number' => 'nullable|integer',
            'delivery_note_number' => 'nullable|integer',
            'invoice_number' => 'nullable|integer',
            'supplier' => 'required|integer|exists:suppliers,id',
        ];
    }
}
