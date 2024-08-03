<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(
 *  schema="Cluster",
 *  title="Cluster",
 * 	@OA\Property(
 * 		property="name",
 * 		type="string"
 * 	),
 * 	@OA\Property(
 * 		property="description",
 * 		type="text"
 * 	)
 * )
 */
class Cluster extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'description',
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

    public function loadProfiles(): HasMany
    {
        return $this->hasMany(LoadProfile::class);
    }
}
