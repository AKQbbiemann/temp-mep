<?php

namespace App\Models;

use App\Models\LoadTypes\BaseLoad;
use App\Models\LoadTypes\ComprehensiveLoad;
use App\Models\LoadTypes\LocalLoad;
use App\Models\LoadTypes\OrganisationLoad;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @OA\Schema(
 *  schema="Competence",
 *  title="Kompetenz",
 * 	@OA\Property(
 * 		property="name",
 * 		type="string"
 * 	),
 * )
 */
class Competence extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
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

    public function loadProfiles(): BelongsToMany
    {
        return $this->belongsToMany(LoadProfile::class, 'load_profiles_competences');
    }
}
