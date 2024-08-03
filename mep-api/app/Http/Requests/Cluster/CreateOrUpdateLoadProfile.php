<?php

namespace App\Http\Requests\Cluster;

use App\Models\LoadProfile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrUpdateLoadProfile extends FormRequest
{
    public function rules(): array
    {
        return [
            'cluster_id' => 'integer|required|exists:clusters,id',
            'profile_id' => 'integer|sometimes|exists:load_profiles,id',
            'description' => 'string|sometimes|nullable',
            'name' => 'string|required|min:2|'.Rule::unique('load_profiles')->ignore($this->profile_id),
            'comprehensive_load' => 'integer|required|min:0|max:100|load_sum:base_load,organisation_load,local_load',
            'base_load' => 'integer|required|min:0|max:100|load_sum:comprehensive_load,organisation_load,local_load',
            'organisation_load' => 'integer|required|min:0|max:100|load_sum:base_load,comprehensive_load,local_load',
            'local_load' => 'integer|required|min:0|max:100|load_sum:base_load,organisation_load,comprehensive_load',
            'full_time_employees' => 'sometimes|decimal:0,2'
        ];
    }
}
