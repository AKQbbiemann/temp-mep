<?php

namespace App\Models;

use Abbasudo\Purity\Traits\Filterable;
use Abbasudo\Purity\Traits\Sortable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *  schema="ProfileChange",
 *  title="ProfileChange",
 * 	@OA\Property(
 * 		property="start_date",
 * 		type="date"
 * 	),
 * 	@OA\Property(
 * 		property="end_date",
 * 		type="date"
 * 	),
 * 	@OA\Property(
 * 		property="fte_change",
 * 		type="float"
 * 	),
 *  @OA\Property(
 * 		property="base_load",
 * 		type="integer"
 * 	),
 *  @OA\Property(
 * 		property="local_load",
 * 		type="integer"
 * 	),
 *  @OA\Property(
 * 		property="comprehensive_load",
 * 		type="integer"
 * 	),
 *  @OA\Property(
 * 		property="organisation_load",
 * 		type="integer"
 * 	),
 *  @OA\Property(
 *      property="reason",
 *      type="string"
 *  )
 * )
 */
class ProfileChange extends Model
{
    use HasFactory;
    use Filterable;
    use Sortable;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'base_load',
        'comprehensive_load',
        'end_date',
        'fte_change',
        'local_load',
        'organisation_load',
        'reason',
        'start_date'
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

    public function loadProfile(): BelongsTo
    {
        return $this->belongsTo(LoadProfile::class, 'load_profile_id', 'id');
    }
}
