<?php

namespace App\Infrastructure\Http\Requests\Listing;

use Illuminate\Foundation\Http\FormRequest;

class DeleteListingRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => $this->route('uuid')
        ]);
    }

    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'id' => 'required|uuid'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
