<?php

namespace App\Models;

use App\Models\LoadTypes\BaseLoad;
use App\Models\LoadTypes\ComprehensiveLoad;
use App\Models\LoadTypes\LocalLoad;
use App\Models\LoadTypes\OrganisationLoad;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Abbasudo\Purity\Traits\Filterable;
use Abbasudo\Purity\Traits\Sortable;

/**
 * @OA\Schema(
 *  schema="LoadProfile",
 *  title="Lastprofil",
 * 	@OA\Property(
 * 		property="name",
 * 		type="string"
 * 	),
 * 	@OA\Property(
 * 		property="description",
 * 		type="text"
 * 	),
 *  @OA\Property(
 * 		property="cluster_id",
 * 		type="integer"
 * 	),
 *  @OA\Property(
 * 		property="base_load",
 * 		type="integer"
 * 	),
 *  @OA\Property(
 * 		property="organisation_load",
 * 		type="integer"
 * 	),
 *  @OA\Property(
 * 		property="comprehensive_load",
 * 		type="integer"
 * 	),
 *  @OA\Property(
 * 		property="local_load",
 * 		type="integer"
 * 	),
 *  @OA\Property(
 *      property="full_time_employees",
 *      type="float"
 *  ),
 * )
 */
class LoadProfile extends Model
{
    use HasFactory;
    const loadTypes = [
        'base',
        'comprehensive',
        'local',
        'organisation'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'base_load',
        'cluster_id',
        'comprehensive_load',
        'description',
        'full_time_employees',
        'local_load',
        'name',
        'organisation_load'
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

    public function cluster(): BelongsTo
    {
        return $this->belongsTo(Cluster::class);
    }

    public function competences(): BelongsToMany
    {
        return $this->belongsToMany(Competence::class, 'load_profiles_competences');
    }

    public function profileChanges(): HasMany
    {
        return $this->hasMany(ProfileChange::class);
    }
}
