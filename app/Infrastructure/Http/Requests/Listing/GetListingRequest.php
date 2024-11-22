<?php

namespace App\Infrastructure\Http\Requests\Listing;

use Illuminate\Foundation\Http\FormRequest;

class GetListingRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'uuid' => $this->route('uuid'),
        ]);
    }

    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'uuid' => 'required|uuid',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
