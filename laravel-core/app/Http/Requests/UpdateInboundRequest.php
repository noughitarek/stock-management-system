<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInboundRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->Has_Permission('inbounds_edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'inbound_at' => 'required|datetime',
            'rubrique_id' => 'required|exists:rubrique,id',
            'commande_note_number' => 'nullable|string|max:255',
            'delivery_note_number' => 'nullable|string|max:255',
            'invoice_number'  => 'nullable|string|max:255',
            'supplier_id' => 'required|exists:rubrique,id',
        ];
    }
}
