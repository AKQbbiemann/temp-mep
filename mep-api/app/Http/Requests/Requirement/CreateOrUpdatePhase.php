<?php

namespace App\Http\Requests\Requirement;

use App\Models\Phase;
use App\Models\Requirement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrUpdatePhase extends FormRequest
{
    public function rules(): array
    {
        return [
            'phase_id' => 'integer|sometimes|exists:phases,id',
            'requirement_id' => 'integer|required|exists:requirements,id',
            'title' => 'string|required|min:1|max:120|'.Rule::unique('phases')->ignore($this->phase_id),
            'start_date' => 'date|required',
            'end_date' => 'date|required',
            'dates_are_strict' => 'boolean|required',
            'description' => 'string|required',
            'probability' => 'string|required|'.Rule::in(Requirement::probabilities),
            'state' => 'string|required|'.Rule::in(Requirement::states),
            'estimation_accuracy' => 'string|required',
            'certainty_of_date' => 'string|required|'.Rule::in(Requirement::certainties),
            'cluster_participation' => 'array|required_without:phase_id',
            'cluster_participation.*.cluster_id' => 'integer|required_without:phase_id|exists:clusters,id',
            'cluster_participation.*.cluster_name' => 'string|required_without:phase_id|exists:clusters,name',
            'cluster_participation.*.profile_id' => 'integer|required_without:phase_id|exists:load_profiles,id',
            'cluster_participation.*.profile_name' => 'string|required_without:phase_id|exists:load_profiles,name',
            'cluster_participation.*.competence_id' => 'integer|nullable|exists:competences,id',
            'cluster_participation.*.competence_name' => 'string|nullable|exists:competences,name',
            'cluster_participation.*.requirement_id' => 'integer|required_without:phase_id|exists:requirements,id',
            'cluster_participation.*.load' => 'integer|required_without:phase_id',
        ];
    }
}
