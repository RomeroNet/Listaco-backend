<?php

namespace App\Infrastructure\Http\Requests\Listing;

use Illuminate\Foundation\Http\FormRequest;

class CreateListingRequest extends FormRequest
{
    protected $redirect = null;

    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
