<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

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
            //
                'image' => 'string|image|max:2048',
                'titre' => 'required|string|max:255',
                'auteur' => 'required|string|max:255',
                'isbn' => 'required|string|max:255|unique:livres,isbn,'.($this->livre ? $this->livre->id : 'null'),
                'description' => 'nullable|string',
                'date_publication' => 'nullable|date',
                'disponible' => 'boolean',  
        ];
    }
}
