<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffSupportTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization handled by policies / middleware.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'reporter_name'   => 'required|string|max:255',
            'reporter_email'  => 'required|string|email|max:255',
            'reporter_phone'  => 'nullable|string|max:50',
            'subject'         => 'required|string|max:255',
            'category'        => 'required|string|max:100',
            'priority'        => 'required|string|in:low,medium,high,urgent',
            'description'     => 'required|string',
            'status'          => "required|string|in:open,pending,in_progress,approved,resolved,cancelled,closed",
            'assigned_to'     => 'nullable|exists:users,id',
            'resolution_notes'=> 'nullable|string',
            'recommendations' => 'nullable|string',
        ];
    }
}
