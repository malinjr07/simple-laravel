<?php

namespace App\Http\Requests;
use OpenApi\Annotations as OA;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *     schema="StoreBookRequest",
 *     required={"title", "author", "publication_year"},
 *     @OA\Property(property="title", type="string", example="100M Leads"),
 *     @OA\Property(property="author", type="string", example="Alex Hormozi"),
 *     @OA\Property(property="publication_year", type="integer", example=2005)
 * )
 */
class StoreBookRequest extends FormRequest
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
            'title' => ['required', 'string'],
            'author' => ['required', 'string'],
            'publicationYear' => ['required', 'integer', 'between:1500,' . now()->year],
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
