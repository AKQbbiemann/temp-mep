<?php

namespace App\Models;


use Abbasudo\Purity\Traits\Filterable;
use Abbasudo\Purity\Traits\Sortable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(
 *  schema="Requirement",
 *  title="Anforderung",
 * 	@OA\Property(
 * 		property="title",
 * 		type="string"
 * 	),
 * 	@OA\Property(
 * 		property="description",
 * 		type="text"
 * 	),
 *  @OA\Property(
 * 		property="type",
 * 		type="string"
 * 	),
 *  @OA\Property(
 * 		property="infos",
 * 		type="text"
 * 	),
 *  @OA\Property(
 * 		property="probability",
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
 * 		property="start_date_is_strict",
 * 		type="boolean"
 * 	),
 *  @OA\Property(
 * 		property="end_date_is_strict",
 * 		type="boolean"
 * 	),
 *  @OA\Property(
 * 		property="time_period_description",
 * 		type="text"
 * 	),
 *  @OA\Property(
 * 		property="state",
 * 		type="string"
 * 	),
 *  @OA\Property(
 * 		property="company_priority",
 * 		type="integer"
 * 	),
 *  @OA\Property(
 * 		property="company_priority_description",
 * 		type="text"
 * 	),
 *  @OA\Property(
 * 		property="requested_priority",
 * 		type="integer"
 * 	),
 *  @OA\Property(
 * 		property="requested_priority_description",
 * 		type="text"
 * 	),
 * )
 */
class Requirement extends Model
{
    use Filterable;
    use Sortable;

    const states = [
        'approved',
        'awaiting_approval',
        'declined',
        'deleted',
        'draft',
        'finished',
        'in_progress',
    ];
    const stateTranslations = [
        'approved' => 'Freigegeben',
        'awaiting_approval' => "Warten auf Freigabe",
        'declined' => 'Abgelehnt',
        'deleted' => 'Gelöscht',
        'draft' => "Draft",
        'finished' => 'Abgeschlossen',
        'in_progress' => 'In Umsetzung',
    ];
    const availableStates = [
        'awaiting_approval',
        'draft',
    ];
    const probabilities = [
        'definite',
        'ordered',
        'potential',
        'probably',
    ];
    const probabilityTranslations = [
        'definite' => 'sicher (>90%)',
        'ordered' => 'beauftragt (100%)',
        'potential' => 'möglich (<=50%)',
        'probably' => 'wahrscheinlich (> 50%)',
    ];
    const typesWithDescription = [
        'Beratung' => 'Beistellung von IT-Experten (technisch/fachlich) der AKQ für ein Vorhaben/Projekt eines Kunden. Unterstützung bei der Konzeption, der Planung, dem Aufbau, der Migration und/oder Betrieb',
        'BID' => 'Erstellung eines Angebots',
        'Entwicklung' => 'Einführung neuer Technologien oder optimieren bereits bestehender Systeme, Produkte oder Prozesse',
        'Implementierung' => 'Neuaufbau oder Einführung einer Systemlandschaft, eines Service, einer Software für Bestandskunde oder Neukunde',
        'Lifecycle' => 'Releasewechsel, Upgrade, Austausch von Hard- oder Software durch neue Version',
        'Migration' => 'Wechsel oder Umzug einer Systemlandschaft oder eines Service zu einer anderen Infrastruktur, in ein anderes Rechenzentrum oder Pattform, Änderung der Bestehenden Architektur (Hard- oder Software + Daten)',
        'Organisation' => 'Einführung oder Veränderung von Prozessen oder   Organisation',
        'Sonstiges'=> '',
        'Transition' => 'Neukunde + Umzug der Bestandsumgebung'
    ];

    const prioritiesWithDescription = [
        1 => 'Ist alternativlos / Abwendung wirtschaftlicher Schaden',
        2 => 'Sollte umgesetzt werden / Technisch notwendig',
        3 => 'Kann zeitlich geschoben, muss aber gemacht werden',
        4 => 'Wenn Zeit übrig ist',
    ];

    const certainties = [
        'certain',
        'undefined',
    ];
    const certaintyTranslations = [
        'certain' => 'Sicher',
        'undefined' => 'Nicht gegeben',
    ];

    const estimation_accuracies = [
        'exact', // genau
        'good_enough', // good enough
        'rough', // grob
        'semi', // mittel
    ];

    const estimationAccuraciesTranslations = [
        'exact' => 'genau',
        'good_enough' => 'good enough',
        'rough' => 'grob',
        'semi' => 'mittel',
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'company_priority',
        'company_priority_description',
        'customer',
        'description',
        'end_date',
        'end_date_is_strict',
        'infos',
        'owner',
        'probability',
        'requested_priority',
        'requested_priority_description',
        'requester',
        'start_date',
        'start_date_is_strict',
        'state',
        'time_period_description',
        'title',
        'type',
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

    public function loadProfiles(): HasMany
    {
        return $this->hasMany(LoadProfile::class);
    }

    public function phases(): HasMany
    {
        return $this->hasMany(Phase::class);
    }
}
