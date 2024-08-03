<?php

namespace App\Http\Helpers;

use App\Models\ClusterParticipation;
use App\Models\Requirement;
use Illuminate\Pagination\LengthAwarePaginator;

class RequirementHelper
{
    public static function clusterParticipations(int $requirementId): array
    {
        $clusterParticipation = [];
        $participations = ClusterParticipation::where('requirement_id', $requirementId)->get()->toArray();
        foreach ($participations as $participation) {
            if(isset($clusterParticipation[$participation['cluster_name']])) {
                $clusterParticipation[$participation['cluster_name']] += $participation['load'];
            } else {
                $clusterParticipation[$participation['cluster_name']] = $participation['load'];
            }
        }

        return $clusterParticipation;
    }

    public static function withClusterParticipation(LengthAwarePaginator $requirements): LengthAwarePaginator
    {
        foreach ($requirements as $requirement) {
            $participations = ClusterParticipation::where('requirement_id', $requirement->id)->get()->toArray();
            $requirement->total_load = array_sum(array_column($participations, 'load'));

            $clusterParticipation = [];
            foreach ($participations as $participation) {
                if(isset($clusterParticipation[$participation['cluster_name']])) {
                    $clusterParticipation[$participation['cluster_name']] += $participation['load'];
                } else {
                    $clusterParticipation[$participation['cluster_name']] = $participation['load'];
                }
            }
            $requirement->cluster_participation = $clusterParticipation;
        }

        return $requirements;
    }

}
