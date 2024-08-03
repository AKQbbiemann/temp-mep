<?php

namespace App\Http\Requests\Cluster;

use Illuminate\Foundation\Http\FormRequest;

class AddOrRemoveLoadProfileCompetence extends FormRequest
{
    public function rules(): array
    {
        return [
            'competence_id' => 'integer|required|exists:competences,id',
            'load_profile_id' => 'integer|required|exists:load_profiles,id',
        ];
    }
}
