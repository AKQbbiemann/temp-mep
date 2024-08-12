<?php

namespace App\Http\Requests\Requirement;

use App\Models\Requirement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrUpdateRequirement extends FormRequest
{
    public function rules(): array
    {
        return [
            'requirement_id' => 'integer|sometimes|exists:requirements,id',
            'title' => 'string|required|min:1|max:120|'.Rule::unique('requirements')->ignore($this->requirement_id),
            'type' => 'string|required|'.Rule::in(array_keys(Requirement::typesWithDescription)),
            'owner' => 'string|sometimes|nullable',
            'creator' => 'string|sometimes|nullable',
            'customer' => 'string|sometimes|nullable',
            'description' => 'string|required',
            'infos' => 'string|sometimes|nullable',
            'probability' => 'string|required|'.Rule::in(Requirement::probabilities),
            'start_date' => 'date|required',
            'end_date' => 'date|required',
            'start_date_is_strict' => 'boolean|required',
            'end_date_is_strict' => 'boolean|required',
            'time_period_description' => 'string|sometimes|nullable',
            'state' => 'string|required|'.Rule::in(Requirement::states),
            'company_priority' => 'integer|sometimes|min:1|max:5|nullable',
            'company_priority_description' => 'string|sometimes|nullable',
            'requested_priority' => 'integer|sometimes|min:1|max:5|nullable',
            'requested_priority_description' => 'string|required',
        ];
    }
}
