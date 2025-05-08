<?php

namespace App\Http\Requests;
use OpenApi\Annotations as OA;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="UpdateBookRequest",
 *     @OA\Property(property="title", type="string", example="New Book"),
 *     @OA\Property(property="author", type="string", example="Jordan Belfort"),
 *     @OA\Property(property="publication_year", type="integer", example=2021)
 * )
 */
class UpdateBookRequest extends FormRequest
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
            'title' => ['sometimes', 'required', 'string'],
            'author' => ['sometimes', 'required', 'string'],
            'publicationYear' => ['sometimes', 'required', 'integer', 'between:1500,' . now()->year],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The book title is required.',
            'author.required' => 'The author name is required.',
            'publicationYear.required' => 'The publication year is required.',
            'publicationYear.between' => 'The publication year must be between 1500 and ' . now()->year . '.',
        ];
    }
}
