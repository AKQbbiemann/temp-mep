<?php

namespace App\Models;

use App\Models\LoadTypes\BaseLoad;
use App\Models\LoadTypes\ComprehensiveLoad;
use App\Models\LoadTypes\LocalLoad;
use App\Models\LoadTypes\OrganisationLoad;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *  schema="Customer",
 *  title="Kunde",
 * 	@OA\Property(
 * 		property="name",
 * 		type="string"
 * 	),
 * 	@OA\Property(
 * 		property="shortcode",
 * 		type="string"
 * 	)
 * )
 */
class Customer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'shortcode',
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
}
