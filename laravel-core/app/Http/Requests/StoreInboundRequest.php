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
        return Auth::user()->Has_Permission('inbounds_create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'inbound_at' => 'required|date_format:Y-m-d\TH:i',
            'rubrique_id' => 'required|exists:rubriques,id',
            'commande_note_number' => 'nullable|string|max:255',
            'delivery_note_number' => 'nullable|string|max:255',
            'invoice_number'  => 'nullable|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',
        ];
    }
}
