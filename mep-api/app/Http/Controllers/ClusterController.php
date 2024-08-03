<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ProfileChangeHelper;
use App\Http\Requests\Cluster\CreateOrUpdateCluster;
use App\Http\Requests\Cluster\CreateOrUpdateLoadProfile;
use App\Http\Requests\Cluster\CreateOrUpdateProfileChange;
use App\Models\Cluster;
use App\Models\LoadProfile;
use App\Models\ProfileChange;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ClusterController extends Controller
{
    /**
     *  Get clusters
     *
     *  @OA\Get(
     *      path="/api/clusters",
     *      operationId="clustersIndex",
     *      tags={"Cluster"},
     *      summary="List of clusters",
     *      description="Returns list of clusters",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *      ),
     *  )
     *
     *  Returns list of clusters
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Cluster::all()->sortBy('name')->toArray()
        ]);
    }

    /**
     *  Get load profiles
     *
     *  @OA\Get(
     *      path="/api/clusters/{clusterId}/profiles/",
     *      operationId="clusterLoadProfiles",
     *      tags={"Cluster"},
     *      summary="List of cluster load profiles",
     *      description="Returns all load profiles of specific cluster",
     *      @OA\Parameter(
     *          name="clusterId",
     *          in="path",
     *          description="ID of cluster",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *      ),
     *  )
     *
     *  Returns list of load profiles
     */
    public function loadProfiles(int $clusterId): JsonResponse
    {
        return response()->json([
            'data' => Cluster::findOrFail($clusterId)->loadProfiles
        ]);
    }

    /**
     *  Get load profile
     *
     *  @OA\Get(
     *      path="/api/clusters/{clusterId}/profiles/{profileId}",
     *      operationId="clusterLoadProfile",
     *      tags={"Cluster"},
     *      summary="Get specific load profile",
     *      description="Returns specific load profile",
     *      @OA\Parameter(
     *          name="clusterId",
     *          in="path",
     *          description="ID of cluster",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="profileId",
     *          in="path",
     *          description="ID of profile",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *      ),
     *  )
     *
     *  Returns load profile
     */
    public function loadProfile(int $clusterId, int $profileId): JsonResponse
    {
        return response()->json([
            'data' => LoadProfile::with('profileChanges')->findOrFail($profileId)
        ]);
    }

    /**
     *  Get profile change
     *
     *  @OA\Get(
     *      path="/api/clusters/{clusterId}/profiles/{profileId}/changes/{changeId}",
     *      operationId="clusterLoadProfileChange",
     *      tags={"Cluster"},
     *      summary="Get specific profile change",
     *      description="Returns specific profile change",
     *      @OA\Parameter(
     *          name="clusterId",
     *          in="path",
     *          description="ID of cluster",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="profileId",
     *          in="path",
     *          description="ID of profile",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="changeId",
     *          in="path",
     *          description="ID of profile change",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *      ),
     *  )
     *
     *  Returns profile change
     */
    public function profileChange(int $clusterId, int $profileId, int $changeId): JsonResponse
    {
        return response()->json([
            'data' => ProfileChange::findOrFail($changeId)
        ]);
    }

    /**
     *  Get cluster details
     *
     *  @OA\Get(
     *      path="/api/clusters/{clusterId}",
     *      operationId="clusterDetail",
     *      tags={"Cluster"},
     *      summary="Cluster details",
     *      description="Returns all cluster details ",
     *      @OA\Parameter(
     *          name="clusterId",
     *          in="path",
     *          description="ID of cluster",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *      ),
     *  )
     *
     *  Returns list of clusters with relations
     */
    public function clusterDetail(int $clusterId): JsonResponse
    {
        return response()->json([
            'data' => Cluster::with(['loadProfiles.competences','loadProfiles.profileChanges'])->findOrFail($clusterId)
        ]);
    }

    /**
     *  Get list of load profiles
     *
     *  @OA\Get(
     *      path="/api/clusters/profiles",
     *      operationId="loadProfileList",
     *      tags={"Cluster"},
     *      summary="List of all load profiles",
     *      description="Returns filterable list of all load profiles with relations",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *      ),
     *  )
     *
     *  Returns list of load profiles
     */
    public function loadProfileList(): JsonResponse
    {
        $profiles = LoadProfile::with(['cluster','competences','profileChanges'])->get();

        return response()->json([
            'data' => $profiles
        ]);
    }

    /**
     *  Get chart data
     *
     *  @OA\Get(
     *      path="/api/clusters/{clusterId}/profiles/{profileId}/chart",
     *      operationId="chartData",
     *      tags={"Cluster"},
     *      summary="Chart data",
     *      description="Returns data of FTE changes to make a graph",
     *      @OA\Parameter(
     *          name="clusterId",
     *          in="path",
     *          description="ID of cluster",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="profileId",
     *          in="path",
     *          description="ID of load profile",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *      ),
     *  )
     *
     *  Returns list of dates with values
     */
    public function chartData(int $clusterId, int $profileId): JsonResponse
    {
        $first = LoadProfile::findOrFail($profileId)->full_time_employees;
        $data = [date("Y-m-d") => (float) $first];
        $changes = ProfileChange::where('load_profile_id', $profileId)
            ->where(function ($query) {
                $query->where('start_date', '>', date('Y-m-d'))
                    ->orWhere('end_date', '>', date('Y-m-d'));
            })->get()->toArray();

        foreach ($changes as $change) {
            array_key_exists($change['start_date'], $data) ? $data[$change['start_date']] += (float) $change['fte_change'] : $data[$change['start_date']] = (float) $change['fte_change'];
            if($change['end_date'] !== null) {
                array_key_exists($change['end_date'], $data) ? $data[$change['end_date']] -= (float) $change['fte_change'] : $data[$change['end_date']] = (float) ($change['fte_change'] * -1);
            }
        }
        ksort($data);

        for ($i = 1; $i < count($data); $i++) {
            $data[array_keys($data)[$i]] += $data[array_keys($data)[$i-1]];
        }

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     *  Create or update cluster
     *
     *  @OA\Post(
     *      path="/api/clusters/",
     *      tags={"Cluster"},
     *      operationId="createOrUpdateCluster",
     *      @OA\RequestBody(
     *          request="createOrUpdateClusterRequestBody",
     *          description="Cluster object that needs to be created or updated",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="cluster_id",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string"
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Authentication error"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Cluster not found"
     *      ),
     *      @OA\Response(
     *          response=405,
     *          description="Validation exception"
     *      ),
     *  )
     */
    public function createOrUpdateCluster(CreateOrUpdateCluster $request): JsonResponse
    {
        if($request->has('cluster_id')) {
            $cluster = Cluster::findOrFail($request->get('cluster_id'));
        } else {
            $cluster = new Cluster();
        }
        $cluster->fill($request->validated());
        $cluster->save();

        return response()->json([
            'cluster_id' => $cluster->id
        ], 201);
    }

    /**
     *  Create or update cluster load profile
     *
     *  @OA\Post(
     *      path="/api/clusters/profiles/",
     *      tags={"Cluster"},
     *      operationId="createOrUpdateLoadProfile",
     *      @OA\RequestBody(
     *          request="createOrUpdateLoadProfileRequestBody",
     *          description="Load profile object that needs to be created or updated",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="cluster_id",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="profile_id",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="comprehensive_load",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="local_load",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="base_load",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="organisation_load",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="full_time_employees",
     *                      type="float"
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Authentication error"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Cluster or profile not found"
     *      ),
     *      @OA\Response(
     *          response=405,
     *          description="Validation exception"
     *      ),
     *  )
     */
    public function createOrUpdateLoadProfile(CreateOrUpdateLoadProfile $request): JsonResponse
    {
        $cluster = Cluster::findOrFail($request->get('cluster_id'));

        if($request->has('profile_id')) {
            $profile = LoadProfile::findOrFail($request->get('profile_id'));
        } else {
            $profile = new LoadProfile();
        }
        $profile->fill($request->validated());
        $profile->save();
        $cluster->loadProfiles()->save($profile);

        return response()->json([
            'profile_id' => $profile->id
        ], 201);
    }

    /**
     *  Create or update profile change
     *
     *  @OA\Post(
     *      path="/api/clusters/profiles/changes",
     *      tags={"Cluster"},
     *      operationId="createOrUpdateProfileChange",
     *      @OA\RequestBody(
     *          request="createOrUpdateProfileChangeRequestBody",
     *          description="Profile change object that needs to be created or updated",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="profile_change_id",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="profile_id",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="start_date",
     *                      type="date"
     *                  ),
     *                  @OA\Property(
     *                      property="end_date",
     *                      type="date"
     *                  ),
     *                  @OA\Property(
     *                      property="fte_change",
     *                      type="float"
     *                  ),
     *                  @OA\Property(
     *                      property="base_load",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="local_load",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="comprehensive_load",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="organisation_load",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="reason",
     *                      type="string"
     *                  ),
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Successfully created / updated profile change"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Authentication error"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Profile change or profile not found"
     *      ),
     *      @OA\Response(
     *          response=405,
     *          description="Validation exception"
     *      ),
     *  )
     */
    public function createOrUpdateProfileChange(int $clusterId, int $profileId, CreateOrUpdateProfileChange $request): JsonResponse
    {
        $profile = LoadProfile::findOrFail($request->get('profile_id'));
        $profileChange = ProfileChangeHelper::createOrUpdateProfileChange($request, $profile);

        return response()->json([
            'profile_change_id' => $profileChange->id
        ]);
    }

    /**
     *  Delete cluster
     *
     *  @OA\Delete(
     *      path="/api/clusters/",
     *      tags={"Cluster"},
     *      operationId="deleteCluster",
     *      @OA\RequestBody(
     *          request="deleteClusterRequestBody",
     *          description="Cluster object that needs to be deleted",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="cluster_id",
     *                      type="integer"
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Authentication error"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Cluster not found"
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Cluster deleted successfully"
     *      ),
     *  )
     */
    public function deleteCluster(Request $request): JsonResponse
    {
        Cluster::findOrFail($request->get('cluster_id'))->delete();

        return response()->json([], 204);
    }

    /**
     *  Delete load profile
     *
     *  @OA\Delete(
     *      path="/api/clusters/profiles/",
     *      tags={"Cluster"},
     *      operationId="deleteLoadProfile",
     *      @OA\RequestBody(
     *          request="deleteClusterRequestBody",
     *          description="Cluster object that needs to be dleted",
     *          required=true,
     *          @OA\MediaType(
     *          mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="profile_id",
     *                      type="integer"
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Authentication error"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Load profile not found"
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Load profile deleted successfully"
     *      ),
     *  )
     */
    public function deleteLoadProfile(Request $request): JsonResponse
    {
        LoadProfile::findOrFail($request->get('profile_id'))->delete();

        return response()->json([], 204);
    }

    /**
     *  Delete Profile Change
     *
     *  @OA\Delete(
     *      path="/api/clusters/profiles/changes",
     *      tags={"Cluster"},
     *      operationId="deleteProfileChange",
     *      @OA\RequestBody(
     *          request="deleteProfileChangeRequestBody",
     *          description="Profile Change object that needs to be deleted",
     *          required=true,
     *          @OA\MediaType(
     *          mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="profile_change_id",
     *                      type="integer"
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Authentication error"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Profile Change not found"
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Profile Change deleted successfully"
     *      ),
     *  )
     */
    public function deleteProfileChange(Request $request): JsonResponse
    {
        $change = ProfileChange::findOrFail($request->get('profile_change_id'));

        //TODO: rework

        // Wenn der Change schon angefangen hat, aber das Ende noch nicht erreicht hat
        if($change->start_date <= date("Y-m-d") && $change->end_date && $change->end_date > date("Y-m-d")) {
            $profile = $change->loadProfile;
            $profile->full_time_employees += ($change->fte_change * -1);
            $profile->save();
        }

        $change->delete();

        return response()->json([], 204);
    }
}
