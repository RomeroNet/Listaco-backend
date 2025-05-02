<?php

namespace App\Item\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateItemRequest extends FormRequest
{
    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'listing_id' => 'required|uuid',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
