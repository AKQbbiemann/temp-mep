<?php

namespace App\Http\Requests\Requirement;

use App\Models\Phase;
use App\Models\Requirement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrUpdateParticipation extends FormRequest
{
    public function rules(): array
    {
        return [
            'participation_id' => 'integer|sometimes|exists:cluster_participations,id',
            'phase_id' => 'integer|required|exists:phases,id',
            'cluster_id' => 'integer|required|exists:clusters,id',
            'cluster_name' => 'string|required|exists:clusters,name',
            'profile_id' => 'integer|required|exists:load_profiles,id',
            'profile_name' => 'string|required|exists:load_profiles,name',
            'competence_id' => 'integer|nullable|exists:competences,id',
            'competence_name' => 'string|nullable|exists:competences,name',
            'requirement_id' => 'integer|required|exists:requirements,id',
            'load' => 'integer|required',
        ];
    }
}
