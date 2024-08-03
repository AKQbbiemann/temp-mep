<?php

namespace App\Http\Requests\Requirement;

use App\Models\Phase;
use App\Models\Requirement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeRequirementState extends FormRequest
{
    public function rules(): array
    {
        return [
            'requirement_id' => 'integer|required|exists:requirements,id',
            'state' => 'string|required|'.Rule::in(Requirement::states),
        ];
    }
}
