<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupportTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled by policies where needed.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'subject'   => 'required|string|max:255',
            'category'  => 'required|string|max:100',
            'priority'  => 'required|string|in:low,medium,high,urgent',
            'description' => 'required|string',
        ];
    }
}
