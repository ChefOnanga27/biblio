<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LivreFormRequest extends FormRequest
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
            'titre' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'isbn' => ['required', 'string', 'max:50', Rule::unique('livres', 'isbn')->ignore($this->route('livre'))],
            'description' => 'nullable|string',
            'date_publication' => 'nullable|date',
            'langue' => 'nullable|string|max:50',
            'nombre_pages' => 'nullable|integer|min:1|max:100000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120|dimensions:min_width=200,min_height=300',
            'supprimer_image' => 'nullable|boolean',
            'pdf' => 'nullable|mimes:pdf|max:512000',
            'categorie_id' => 'required|exists:categories,id',
            'genre_id' => 'nullable|exists:genres,id',
            'disponible' => 'boolean',
        ];
    }
}
