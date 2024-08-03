<?php

namespace App\Http\Requests\Cluster;

use Illuminate\Foundation\Http\FormRequest;

class AddOrRemoveClusterCompetence extends FormRequest
{
    public function rules(): array
    {
        return [
            'cluster_id' => 'integer|required|exists:clusters,id',
            'competence_id' => 'integer|required|exists:competences,id',
        ];
    }
}
