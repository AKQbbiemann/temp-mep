<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(
 *  schema="Phase",
 *  title="Phase",
 *  @OA\Property(
 * 		property="title",
 * 		type="string"
 * 	),
 *  @OA\Property(
 * 		property="description",
 * 		type="text"
 * 	),
 * 	@OA\Property(
 * 		property="state",
 * 		type="string"
 * 	),
 *  @OA\Property(
 * 		property="start_date",
 * 		type="date"
 * 	),
 *  @OA\Property(
 * 		property="end_date",
 * 		type="date"
 * 	),
 *  @OA\Property(
 * 		property="dates_are_strict",
 * 		type="boolean"
 * 	),
 *  @OA\Property(
 * 		property="probability",
 * 		type="string"
 * 	),
 *  @OA\Property(
 * 		property="estimation_accuracy",
 * 		type="string"
 * 	),
 *  @OA\Property(
 * 		property="certainty_of_date",
 * 		type="string"
 * 	),
 * )
 */
class Phase extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'certainty_of_date',
        'dates_are_strict',
        'description',
        'end_date',
        'estimation_accuracy',
        'probability',
        'start_date',
        'state',
        'title',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'end_date' => 'date',
        'start_date' => 'date',
        'updated_at' => 'datetime',
    ];

    public function requirement(): BelongsTo
    {
        return $this->belongsTo(Requirement::class);
    }

    public function clusterParticipations(): HasMany
    {
        return $this->hasMany(ClusterParticipation::class);
    }
}
