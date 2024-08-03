<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cluster\AddOrRemoveClusterCompetence;
use App\Http\Requests\Cluster\AddOrRemoveLoadProfileCompetence;
use App\Http\Requests\CreateOrUpdateCompetence;
use App\Models\Cluster;
use App\Models\Competence;
use App\Models\LoadProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompetenceController extends Controller
{
    /**
     *  Get competences
     *
     *  @OA\Get(
     *      path="/api/competences/",
     *      operationId="competencesIndex",
     *      tags={"Competence"},
     *      summary="List of competences",
     *      description="Returns list of competences",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *      ),
     *  )
     *
     *  Returns list of competences
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => Competence::all()
        ]);
    }

    /**
     *  Get competence
     *
     *  @OA\Get(
     *      path="/api/competences/{competenceId}",
     *      operationId="competencesSingle",
     *      tags={"Competence"},
     *      summary="Specific competence",
     *      description="Returns the specific competence",
     *      @OA\Parameter(
     *          name="competenceId",
     *          in="path",
     *          description="ID of competence",
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
     *  Returns competence
     */
    public function getCompetenceById(int $competenceId): JsonResponse
    {
        return response()->json([
            'data' => Competence::findOrFail($competenceId)
        ]);
    }

    /**
     *  Create or update competence
     *
     *  @OA\Post(
     *      path="/api/competences/",
     *      tags={"Competence"},
     *      operationId="createOrUpdateCompetence",
     *      @OA\RequestBody(
     *          request="createOrUpdateCompetenceRequestBody",
     *          description="Competence object that needs to be created or updated",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="competence_id",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="name",
     *                      type="string"
     *                  ),
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Successfully created"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Authentication error"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Competence not found"
     *      ),
     *      @OA\Response(
     *          response=405,
     *          description="Validation exception"
     *      ),
     *  )
     */
    public function createOrUpdateCompetence(CreateOrUpdateCompetence $request): JsonResponse
    {
        if($request->has('competence_id')) {
            $competence = Competence::findOrFail($request->get('competence_id'));
        } else {
            $competence = new Competence();
        }
        $competence->fill($request->validated());
        $competence->save();

        return response()->json([
            'competence_id' => $competence->id
        ]);
    }

    /**
     *  Delete competence
     *
     *  @OA\Delete(
     *      path="/api/competences/",
     *      tags={"Competence"},
     *      operationId="deleteCompetence",
     *      @OA\RequestBody(
     *          request="deleteCompetenceRequestBody",
     *          description="Competence object that needs to be deleted",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="competence_id",
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
     *          description="Competence not found"
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Competence deleted successfully"
     *      ),
     *  )
     */
    public function deleteCompetence(Request $request): JsonResponse
    {
        Competence::findOrFail($request->get('competence_id'))->delete();

        return response()->json([], 204);
    }

    /**
     *  Add competence to load profile
     *
     *  @OA\Put(
     *      path="/api/competences/profiles/",
     *      tags={"Competence"},
     *      operationId="addLoadProfileCompetence",
     *      @OA\RequestBody(
     *          request="addLoadProfileCompetenceRequestBody",
     *          description="Profile ID and Competence ID",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="load_profile_id",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="competence_id",
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
     *          description="Loadprofile or competence not found"
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Competence added successfully"
     *      ),
     *  )
     */
    public function addCompetenceToLoadProfile(AddOrRemoveLoadProfileCompetence $request): JsonResponse
    {
        LoadProfile::find($request->get('load_profile_id'))->competences()->attach($request->get('competence_id'));

        return response()->json([], 201);
    }

    /**
     *  Remove competence from load profile
     *
     *  @OA\Delete(
     *      path="/api/competences/profiles/",
     *      tags={"Competence"},
     *      operationId="removeLoadProfileCompetence",
     *      @OA\RequestBody(
     *          request="removeLoadProfileCompetenceRequestBody",
     *          description="Profile ID and Competence ID",
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="load_profile_id",
     *                      type="integer"
     *                  ),
     *                  @OA\Property(
     *                      property="competence_id",
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
     *          description="Loadprofile or competence not found"
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Competence removed successfully"
     *      ),
     *  )
     */
    public function removeCompetenceFromLoadProfile(AddOrRemoveLoadProfileCompetence $request): JsonResponse
    {
        LoadProfile::find($request->get('load_profile_id'))->competences()->detach($request->get('competence_id'));

        return response()->json([], 204);
    }
}
