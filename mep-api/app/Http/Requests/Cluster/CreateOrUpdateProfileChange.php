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
            'profile_change_id' => 'integer|sometimes|exists:profile_changes,id',
            'comprehensive_load' => 'integer|required|min:0|max:100|load_sum:base_load,organisation_load,local_load',
            'base_load' => 'integer|required|min:0|max:100|load_sum:comprehensive_load,organisation_load,local_load',
            'organisation_load' => 'integer|required|min:0|max:100|load_sum:base_load,comprehensive_load,local_load',
            'local_load' => 'integer|required|min:0|max:100|load_sum:base_load,organisation_load,comprehensive_load',
            'start_date' => 'date|required|after_or_equal:today',
            'end_date' => 'date|sometimes|nullable|after_or_equal:'.$this->start_date,
            'fte_change' => 'required|decimal:0,2',
            'reason' => 'string|required'
        ];
    }
}
