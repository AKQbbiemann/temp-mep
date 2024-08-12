<?php

namespace App\Http\Requests\Cluster;

use App\Models\LoadProfile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrUpdateProfileChange extends FormRequest
{
    public function rules(): array
    {
        return [
            'profile_id' => 'integer|required|exists:load_profiles,id',
            'employee_change_id' => 'integer|sometimes|exists:profile_changes,id',
            'comprehensive_load' => 'integer|min:0|max:100|nullable',
            'base_load' => 'integer|min:0|max:100|nullable',
            'organisation_load' => 'integer|min:0|max:100|nullable',
            'local_load' => 'integer|min:0|max:100|nullable',
            'start_date' => 'date|required|after_or_equal:today',
            'end_date' => 'date|sometimes|nullable|after_or_equal:'.$this->start_date,
            'fte_change' => 'decimal:0,2',
            'reason' => 'string|required'
        ];
    }
}
