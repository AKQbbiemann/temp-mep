<?php

namespace App\Http\Controllers;


use App\Models\Cluster;
use App\Models\Competence;
use App\Models\LoadProfile;
use App\Models\Requirement;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      x={
 *          "logo": {
 *              "url": "https://via.placeholder.com/190x90.png?text=L5-Swagger"
 *          }
 *      },
 *      title="MEP API",
 *      description="MEP API",
 * )
 *
 */


class AppController extends Controller
{
    public function version(): JsonResponse
    {
        return response()->json([
            'version' => env('API_VERSION', 'LOCAL-1.0.0')
        ]);
    }

    public function reset(): JsonResponse
    {
        Artisan::call('db:wipe');
        Artisan::call('migrate');

        return response()->json([
            'success' => true
        ]);
    }

    public function migrateCompetences(): JsonResponse
    {
        $response = Http::get('https://mep.team41dev.oss.msvc.akqui.net/migration/competences?token=12345');

        if(!$response->ok()) {
            return response()->json([
                'message' => 'MEP Prototype migration endpoint failure'
            ], 500);
        }

        $competences = $response->json()['data'];
        $count = 0;
        foreach($competences as $competence) {
            Competence::updateOrCreate([
                'name' => $competence['name']
            ]);
            $count++;
        }



        return response()->json([
            'message' => 'Success: '.$count.' competences migrated'
        ]);
    }

    public function migrateClusters(): JsonResponse
    {
        $response = Http::get('https://mep.team41dev.oss.msvc.akqui.net/migration/clusters?token=12345');

        if(!$response->ok()) {
            return response()->json([
                'message' => 'MEP Prototype migration endpoint failure'
            ], 500);
        }

        $clusters = $response->json()['data'];
        $count = 0;
        foreach($clusters as $cluster) {
            Cluster::updateOrCreate([
                'name' => $cluster['name'],
                'description' => $cluster['description']
            ]);
            $count++;
        }

        return response()->json([
            'message' => 'Success: '.$count.' clusters migrated'
        ]);
    }

    public function migrateProfiles(): JsonResponse
    {
        $response = Http::get('https://mep.team41dev.oss.msvc.akqui.net/migration/profiles?token=12345');

        if(!$response->ok()) {
            return response()->json([
                'message' => 'MEP Prototype migration endpoint failure'
            ], 500);
        }

        $profiles = $response->json()['data'];
        $count = 0;
        foreach($profiles as $profile) {
            if(!$profile || !isset($profile['base_load'])) {
                continue;
            }

            $cluster = Cluster::where('name', $profile['cluster']['name'])->first();

            if(!$cluster) {
                echo "CLUSTER NOT FOUND!";
                continue;
            }

            $loadProfile = LoadProfile::updateOrCreate([
                'base_load' => $profile['base_load']['load_percentage'],
                'cluster_id' => $cluster->id,
                'comprehensive_load' => $profile['comprehensive_load']['load_percentage'],
                'description' => $profile['description'],
                'local_load' => $profile['local_load']['load_percentage'],
                'name' => $profile['name'],
                'organisation_load' => $profile['organisation_load']['load_percentage'],
            ]);
            $count++;

            foreach($profile['skills'] as $skill) {
                $competence = Competence::where('name', $skill['name'])->first();
                $loadProfile->competences()->attach($competence);
            }
        }

        return response()->json([
            'message' => 'Success: '.$count.' clusters migrated'
        ]);
    }

    public function migrateRequirements(): JsonResponse
    {
        $response = Http::get('https://mep.team41dev.oss.msvc.akqui.net/migration/requirements?token=12345');

        if(!$response->ok()) {
            return response()->json([
                'message' => 'MEP Prototype migration endpoint failure'
            ], 500);
        }

        $requirements = $response->json()['data'];
        $count = 0;
        $phaseCount = 0;
        foreach($requirements as $requirement) {
            if($requirement['status'] === "deleted") {
                continue;
            }

            $probability = "potential";
            if($requirement['probability'] === "sicher") {
                $probability = "definite";
            } elseif($requirement['probability'] === "wahrscheinlich") {
                $probability = "probably";
            } elseif($requirement['probability'] === "beauftragt") {
                $probability = "ordered";
            }

            $createdRequirement = Requirement::updateOrCreate([
                'title' => $requirement['name'],
                'type' => $requirement['type'],
                'requester' => $requirement['requester'],
                'customer' => $requirement['customer'],
                'description' => $requirement['description'],
                'infos' => $requirement['info_link'],
                'probability' => $probability,
                'start_date' => $requirement['start_date'],
                'end_date' => $requirement['end_date'],
                'start_date_is_strict' => $requirement['start_date_is_strict'],
                'end_date_is_strict' => $requirement['end_date_is_strict'],
                'time_period_description' => $requirement['period_argumentation'],
                'state' => $requirement['status'],
                'company_priority' => $requirement['priority'],
                'company_priority_description' => $requirement['priority_argumentation'],
                'requested_priority' => $requirement['wish_priority'],
                'requested_priority_description' => $requirement['wish_priority_argumentation'] ?? " "
            ]);
            $count++;
            foreach($requirement['milestones'] as $milestone) {
                $probability = "potential";
                if($milestone['probability_of_occurrence'] === "sicher") {
                    $probability = "definite";
                } elseif($milestone['probability_of_occurrence'] === "wahrscheinlich") {
                    $probability = "probably";
                } elseif($milestone['probability_of_occurrence'] === "beauftragt") {
                    $probability = "ordered";
                }

                $certainty = 'certain';
                if($milestone['certainty_of_date'] !== "sicher") {
                    $certainty = 'undefined';
                }

                $estimationAccuracy = 'exact';
                if($milestone['estimation_accuracy'] === 'mittel') {
                    $estimationAccuracy = 'semi';
                } elseif($milestone['estimation_accuracy'] === 'good enough'){
                    $estimationAccuracy = 'good_enough';
                } elseif($milestone['estimation_accuracy'] === 'grob') {
                    $estimationAccuracy = 'rough';
                }


                $projectPhase = $createdRequirement->phases()->updateOrCreate([
                    'certainty_of_date' => $certainty,
                    'dates_are_strict' => $milestone['strict_timing'],
                    'description' => $milestone['description'] ?: " ",
                    'end_date' => $milestone['end_date'],
                    'estimation_accuracy' => $estimationAccuracy,
                    'probability' => $probability,
                    'start_date' => $milestone['start_date'],
                    'state' => $milestone['status'] ?: "awaiting_approval",
                    'title' => $milestone['name'],
                ]);
                $phaseCount++;

                foreach($milestone['cluster_participation'] as $participation) {

                    $participationCluster = Cluster::where('name', $participation['cluster_name'])->first();
                    $participationProfile = LoadProfile::where('name', $participation['profile_name'])->
                        where('cluster_id', $participationCluster->id)->first();

                    if(!$participationCluster || !$participationProfile) {
                        continue;
                    }


                    $projectPhase->clusterParticipations()->create([
                        'cluster_id' => $participationCluster->id,
                        'cluster_name' => $participationCluster->name,
                        'profile_id' => $participationProfile->id,
                        'profile_name' => $participationProfile->name,
                        'requirement_id' => $createdRequirement->id,
                        'load' => $participation['pt'],
                    ]);
                }
            }
        }



        return response()->json([
            'message' => 'Success: '.$count.' requirements and '.$phaseCount.' phases migrated'
        ]);
    }

}
