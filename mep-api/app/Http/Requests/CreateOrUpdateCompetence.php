<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrUpdateCompetence extends FormRequest
{
    public function rules(): array
    {
        return [
            'competence_id' => 'integer|sometimes|exists:competences,id',
            'name' => 'string|required|min:2|unique:competences,name',
        ];
    }
}
