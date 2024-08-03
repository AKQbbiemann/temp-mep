<?php

namespace App\Http\Requests\Cluster;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrUpdateCluster extends FormRequest
{
    public function rules(): array
    {
        return [
            'cluster_id' => 'integer|sometimes|exists:clusters,id',
            'description' => 'string|sometimes|nullable',
            'name' => 'string|required|min:2|'.Rule::unique('clusters')->ignore($this->cluster_id),
        ];
    }
}
