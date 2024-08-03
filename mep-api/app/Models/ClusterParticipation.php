<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *  schema="ClusterParticipation",
 *  title="Cluster Beteiligung",
 * 	@OA\Property(
 * 		property="cluster_id",
 * 		type="integer"
 * 	),
 * 	@OA\Property(
 * 		property="cluster_name",
 * 		type="text"
 * 	),
 *  @OA\Property(
 * 		property="profile_id",
 * 		type="integer"
 * 	),
 *  @OA\Property(
 * 		property="profile_name",
 * 		type="text"
 * 	),
 *  @OA\Property(
 * 		property="competence_id",
 * 		type="integer"
 * 	),
 *  @OA\Property(
 * 		property="competence_name",
 * 		type="text"
 * 	),
 *  @OA\Property(
 * 		property="requirement_id",
 * 		type="integer"
 * 	),
 *  @OA\Property(
 * 		property="load",
 * 		type="integer"
 * 	),
 * )
 */
class ClusterParticipation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'cluster_id',
        'cluster_name',
        'competence_id',
        'competence_name',
        'load',
        'profile_id',
        'profile_name',
        'requirement_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function phase(): BelongsTo
    {
        return $this->belongsTo(Phase::class);
    }
}
