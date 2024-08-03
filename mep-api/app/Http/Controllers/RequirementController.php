<?php

namespace App\Http\Controllers;

use App\Http\Helpers\RequirementHelper;
use App\Http\Requests\Requirement\ChangeRequirementState;
use App\Http\Requests\Requirement\CreateOrUpdateParticipation;
use App\Http\Requests\Requirement\CreateOrUpdatePhase;
use App\Http\Requests\Requirement\CreateOrUpdateRequirement;
use App\Models\ClusterParticipation;
use App\Models\Requirement;
use App\Models\Phase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class RequirementController extends Controller
{
    /**
     *  Get Requirements
     *
     *  @OA\Get(
     *      path="/api/requirements",
     *      operationId="requirementsIndex",
     *      tags={"Requirement"},
     *      summary="List of requirements",
     *      description="Returns filterable list of all requirements with relations",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *      ),
     *  )
     *
     *  Returns list of requirements
     */
    public function index(): JsonResponse
    {
        $requirements = Requirement::with('phases.clusterParticipations')->filter()->sort()->paginate(25);

        return response()->json([
            'data' => RequirementHelper::withClusterParticipation($requirements)
        ]);
    }

    /**
     *  Get requirement
     *
     *  @OA\Get(
     *      path="/api/requirements/{requirementId}",
     *      operationId="requirementDetail",
     *      tags={"Requirement"},
     *      summary="Requirement details",
     *      description="Returns specific requirement with relations",
     *      @OA\Parameter(
     *          name="requirementId",
     *          in="path",
     *          description="ID of requirement",
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
     *  Returns requirement
     */
    public function requirementDetail(int $requirementId): JsonResponse
    {

        return response()->json([
            'data' => Requirement::with('phases.clusterParticipations')->findOrFail($requirementId),
            'cluster_participation' => RequirementHelper::clusterParticipations($requirementId)
        ]);
    }

    /**
     *  Get phase
     *
     *  @OA\Get(
     *      path="/api/requirements/phase/{phaseId}",
     *      operationId="phaseDetail",
     *      tags={"Requirement"},
     *      summary="Phase details",
     *      description="Returns specific phase with relations",
     *      @OA\Parameter(
     *          name="phaseId",
     *          in="path",
     *          description="ID of phase",
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
     *  Returns phase
     */
    public function phaseDetail(int $phaseId): JsonResponse
    {
        return response()->json([
            'data' => Phase::with('clusterParticipations')->findOrFail($phaseId)
        ]);
    }

    /**
     *  Get cluster participation
     *
     *  @OA\Get(
     *      path="/api/requirements/phase/participation/{participationId}",
     *      operationId="participationDetail",
     *      tags={"Requirement"},
     *      summary="Get specific cluster participation",
     *      description="Returns the specific cluster participation",
     *      @OA\Parameter(
     *          name="participationId",
     *          in="path",
     *          description="ID of cluster participation",
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
     *  Returns cluster participation
     */
    public function getParticipationById(int $participationId): JsonResponse
    {
        return response()->json([
            'data' => ClusterParticipation::findOrFail($participationId)
        ]);
    }

    /**
     *  @OA\Get(
     *      path="/api/requirements/dropdown-data",
     *      operationId="requirementsDropdown",
     *      tags={"Requirement"},
     *      summary="Lists with Data for Dropdowns",
     *      description="Returns lists with Data for Dropdowns",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *      ),
     *  )
     *
     *  Returns lists with Data for Dropdowns
     */
    public function dropdownData(): JsonResponse
    {

        return response()->json([
            'states' => Requirement::stateTranslations,
            'probabilities' => Requirement::probabilityTranslations,
            'types' => Requirement::typesWithDescription,
            'priorities' => Requirement::prioritiesWithDescription,
            'certainty' => Requirement::certaintyTranslations,
            'estimation_accuracies' =>  Requirement::estimationAccuraciesTranslations
        ]);
    }

    /**
     *  Create or update requirement
     *
     *  @OA\Post(
     *      path="/api/requirements/",
     *      tags={"Requirement"},
     *      operationId="createOrUpdateRequirement",
     *      @OA\RequestBody(
     *          request="createOrUpdateRequirementRequestBody",
     *          description="Requirement object that needs to be created or updated",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="requirement_id",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="title",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="type",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="owner",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="creator",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="customer",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="infos",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="probability",
     *                      type="string"
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
     *                      property="start_date_is_strict",
     *                      type="boolean"
     *                  ),
     *                  @OA\Property(
     *                      property="end_date_is_strict",
     *                      type="boolean"
     *                  ),
     *                  @OA\Property(
     *                      property="time_period_description",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="state",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="company_priority",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="company_priority_description",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="requested_priority",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="requested_priority_description",
     *                      type="string"
     *                  ),
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Requirement successfully created/updated"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Authentication error"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Requirement not found"
     *      ),
     *      @OA\Response(
     *          response=405,
     *          description="Validation exception"
     *      ),
     *  )
     */
    public function createOrUpdateRequirement(CreateOrUpdateRequirement $request): JsonResponse
    {
        if($request->has('requirement_id')) {
            $requirement = Requirement::findOrFail($request->get('requirement_id'));
        } else {
            $requirement = new Requirement();
        }

        $requirement->fill($request->validated());
        $requirement->save();

        return response()->json([
            'id' => $requirement->id
        ], 200);
    }

    /**
     *  Create or update project phase
     *
     *  @OA\Post(
     *      path="/api/requirements/phase",
     *      tags={"Requirement"},
     *      operationId="createOrUpdatePhase",
     *      @OA\RequestBody(
     *          request="createOrUpdatePhaseRequestBody",
     *          description="Phase object that needs to be created or updated",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="phase_id",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="requirement_id",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="title",
     *                      type="string"
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
     *                      property="dates_are_strict",
     *                      type="boolean"
     *                  ),
     *                  @OA\Property(
     *                      property="description",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="probability",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="state",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="estimation_accuracy",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="certainty_of_date",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="cluster_participation",
     *                      type="array",
     *                      @OA\Items(
     *                          @OA\Property(
     *                              property="cluster_id",
     *                              type="integer"
     *                          ),
     *                          @OA\Property(
     *                              property="cluster_name",
     *                              type="string"
     *                          ),
     *                          @OA\Property(
     *                              property="profile_id",
     *                              type="integer"
     *                          ),
     *                          @OA\Property(
     *                              property="profile_name",
     *                              type="string"
     *                          ),
     *                          @OA\Property(
     *                              property="competence_id",
     *                              type="integer"
     *                          ),
     *                          @OA\Property(
     *                              property="competence_name",
     *                              type="string"
     *                          ),
     *                          @OA\Property(
     *                              property="requirement_id",
     *                              type="integer"
     *                          ),
     *                          @OA\Property(
     *                              property="load",
     *                              type="integer"
     *                          ),
     *                      ),
     *                  ),
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Phase successfully created/updated"
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
    public function createOrUpdatePhase(CreateOrUpdatePhase $request): JsonResponse
    {
        $requirement = Requirement::find($request->get('requirement_id'));
        if($request->has('phase_id')) {
            $phaseId = $request->get('phase_id');
            $phase = Phase::find($phaseId);
            $phase->update(array_diff_key($request->validated(), array_flip(["cluster_participation"])));
        } else {
            $phaseId = $requirement->phases()->create(array_diff_key($request->validated(), array_flip(["cluster_participation"])))->id;
            foreach ($request->validated()['cluster_participation'] as $clusterParticipation) {
                Phase::find($phaseId)->clusterParticipations()->create($clusterParticipation);
            }
        }

        return response()->json([
            'id' => $phaseId
        ], 200);
    }

    /**
     *  Create or update cluster participation
     *
     *  @OA\Post(
     *      path="/api/requirements/phase/participation",
     *      tags={"Requirement"},
     *      operationId="createOrUpdateParticipation",
     *      @OA\RequestBody(
     *          request="createOrUpdateParticipationRequestBody",
     *          description="Cluster participation object that needs to be created or updated",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="participation_id",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="phase_id",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="cluster_id",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="cluster_name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="profile_id",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="profile_name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="competence_id",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="competence_name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="requirement_id",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="load",
     *                      type="integer"
     *                  ),
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Cluster participation successfully created/updated"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Authentication error"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Cluster participation or phase not found"
     *      ),
     *      @OA\Response(
     *          response=405,
     *          description="Validation exception"
     *      ),
     *  )
     */
    public function createOrUpdateParticipation(CreateOrUpdateParticipation $request): JsonResponse
    {
        if($request->has('participation_id')) {
            $participationId = $request->get('participation_id');
            $participation = ClusterParticipation::find($participationId);
            $participation->update($request->validated());
        }else {
            $phaseId = $request->get('phase_id');
            $participationId = Phase::find($phaseId)->clusterParticipations()->create($request->validated())->id;
        }

        return response()->json([
            'id' => $participationId
        ], 200);
    }

    /**
     *  Delete requirement
     *
     *  @OA\Delete(
     *      path="/api/requirements/",
     *      tags={"Requirement"},
     *      operationId="deleteRequirement",
     *      @OA\RequestBody(
     *          request="deleteRequirementRequestBody",
     *          description="Requirement object that needs to be deleted",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="requirement_id",
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
     *          description="Requirement not found"
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Requirement deleted successfully"
     *      ),
     *  )
     */
    public function deleteRequirement(Request $request): JsonResponse
    {
        Requirement::findOrFail($request->get('requirement_id'))->delete();

        return response()->json([], 204);
    }

    /**
     *  Delete phase
     *
     *  @OA\Delete(
     *      path="/api/requirements/phase",
     *      tags={"Requirement"},
     *      operationId="deletePhase",
     *      @OA\RequestBody(
     *          request="deletePhaseRequestBody",
     *          description="Phase object that needs to be deleted",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="phase_id",
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
     *          description="Phase not found"
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Phase deleted successfully"
     *      ),
     *  )
     */
    public function deletePhase(Request $request): JsonResponse
    {
        Phase::findOrFail($request->get('phase_id'))->delete();

        return response()->json([], 204);
    }

    /**
     *  Delete cluster participation
     *
     *  @OA\Delete(
     *      path="/api/requirements/phase/participation",
     *      tags={"Requirement"},
     *      operationId="deleteParticipation",
     *      @OA\RequestBody(
     *          request="deleteParticipationRequestBody",
     *          description="Cluster participation object that needs to be deleted",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="participation_id",
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
     *          description="Cluster participation not found"
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Cluster participation deleted successfully"
     *      ),
     *  )
     */
    public function deleteParticipation(Request $request): JsonResponse
    {
        ClusterParticipation::findOrFail($request->get('participation_id'))->delete();

        return response()->json([], 204);
    }

    /**
     *  Change state of requirement
     *
     *  @OA\Post(
     *      path="/api/requirements/state",
     *      tags={"Requirement"},
     *      operationId="changeRequirementState",
     *      @OA\RequestBody(
     *          request="changeRequirementStateRequestBody",
     *          description="Requirement ID + new state",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="requirement_id",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="state",
     *                      type="string"
     *                  ),
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="State successfully changed"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Authentication error"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Requirement not found"
     *      ),
     *      @OA\Response(
     *          response=405,
     *          description="Validation exception"
     *      ),
     *  )
     */
    public function changeRequirementState(ChangeRequirementState $request): JsonResponse
    {
        $requirement = Requirement::find($request->get('requirement_id'));
        $requirement->update($request->validated());
        $requirement->save();

        return response()->json([], 200);
    }


}
