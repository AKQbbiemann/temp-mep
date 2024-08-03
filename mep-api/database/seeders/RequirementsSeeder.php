<?php

namespace Database\Seeders;

use App\Models\Cluster;
use App\Models\Requirement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;


class RequirementsSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /*$cluster = Cluster::where('name', 'Internal Services')->get()->first();
        $serviceDelivery = Cluster::where('name', 'Service Delivery')->get()->first();
        $requirement = new Requirement();
        $requirement->title = "Test Anforderung 1";
        $requirement->type = "project";
        $requirement->customer = "ESA";
        $requirement->description = "Dies ist eine Beschreibung zur Testanforderung";
        $requirement->infos = "Dies ist eine Info zur Testanforderung";
        $requirement->probability = 'sure';
        $requirement->start_date = new \DateTime();
        $requirement->end_date = new \DateTime('+3 months');
        $requirement->start_date_is_strict = true;
        $requirement->end_date_is_strict = true;
        $requirement->time_period_description = 'Ich bin eine Zeitspannenbeschreibung';
        $requirement->state = 'awaiting_approval';
        $requirement->company_priority = 1;
        $requirement->company_priority_description = "Weil Kunde involviert, Prio 1";
        $requirement->requested_priority = 1;
        $requirement->requested_priority_description = "Weil Kunde involviert, Prio 1";
        $requirement->save();

        // Add Project Phases
        $requirement->phases()->create([
            'title' => 'Phase 1',
            'start_date' => new \DateTime(),
            'end_date' => new \DateTime('+1 month'),
            'dates_are_strict' => true,
            'description' => 'Kickoff',
            'probability' => 'definite',
            'state' => 'Ja',
        ])->clusterParticipations()->create([
            'cluster_id' => $serviceDelivery->id,
            'cluster_name' => $serviceDelivery->name,
            'profile_id' => $serviceDelivery->loadProfiles()->first()->id,
            'profile_name' => $serviceDelivery->loadProfiles()->first()->name,
            'competence_id' => 1,
            'competence_name' => 'Beratung und Planung',
            'requirement_id' => (Integer) $requirement->id,
            'load' => rand(10,30)
        ]);

        $requirement->phases()->create([
            'title' => 'Entwicklungsphase',
            'start_date' => new \DateTime('+1 month'),
            'end_date' => new \DateTime('+2 months'),
            'dates_are_strict' => true,
            'description' => 'Entwicklung',
            'probability' => 'definite',
            'state' => 'Ja',
        ])->clusterParticipations()->create([
            'cluster_id' => $cluster->id,
            'cluster_name' => $cluster->name,
            'profile_id' => $cluster->loadProfiles()->get()->toArray()[2]['id'],
            'profile_name' => $cluster->loadProfiles()->get()->toArray()[2]['name'],
            'competence_id' => 2,
            'competence_name' => 'Applikations-Entwicklung',
            'requirement_id' => (Integer) $requirement->id,
            'load' => rand(10,65)
        ]);

        $requirement->phases()->create([
            'title' => 'Testphase',
            'start_date' => new \DateTime('+2 months'),
            'end_date' => new \DateTime('+3 months'),
            'dates_are_strict' => true,
            'description' => 'Abnahme',
            'probability' => 'definite',
            'state' => 'Ja',
        ])->clusterParticipations()->create([
            'cluster_id' => $cluster->id,
            'cluster_name' => $cluster->name,
            'profile_id' => $cluster->loadProfiles()->get()->toArray()[2]['id'],
            'profile_name' => $cluster->loadProfiles()->get()->toArray()[2]['name'],
            'competence_id' => 3,
            'competence_name' => 'Testing & Dokumentation',
            'requirement_id' => (Integer) $requirement->id,
            'load' => 30
        ]);

        // SECOND REQUIREMENT --------------------------------------------------------
        $requirement = new Requirement();
        $requirement->title = "Bid Test";
        $requirement->type = "bid";
        $requirement->customer = "ESA";
        $requirement->description = "Dies ist eine weitere Anforderung";
        $requirement->infos = "Dies ist eine Info zur Testanforderung";
        $requirement->probability = 'sure';
        $requirement->start_date = new \DateTime('+1 month');
        $requirement->end_date = new \DateTime('+9 months');
        $requirement->start_date_is_strict = true;
        $requirement->end_date_is_strict = true;
        $requirement->time_period_description = 'Ich bin eine Zeitspannenbeschreibung';
        $requirement->state = 'awaiting_approval';
        $requirement->company_priority = 1;
        $requirement->company_priority_description = "Weil Kunde involviert, Prio 1";
        $requirement->requested_priority = 1;
        $requirement->requested_priority_description = "Weil Kunde involviert, Prio 1";
        $requirement->save();


        // Add Project Phases
        $requirement->phases()->create([
            'title' => 'Erstes Angebot',
            'start_date' => new \DateTime('+1 month'),
            'end_date' => new \DateTime('+2 month'),
            'dates_are_strict' => true,
            'description' => '',
            'probability' => 'definite',
            'state' => 'Ja',
        ])->clusterParticipations()->create([
            'cluster_id' => $serviceDelivery->id,
            'cluster_name' => $serviceDelivery->name,
            'profile_id' => $serviceDelivery->loadProfiles()->first()->id,
            'profile_name' => $serviceDelivery->loadProfiles()->first()->name,
            'competence_id' => 1,
            'competence_name' => 'Beratung und Planung',
            'requirement_id' => (Integer) $requirement->id,
            'load' => rand(10,30)
        ]);

        $requirement->phases()->create([
            'title' => 'Angebotspräsentation',
            'start_date' => new \DateTime('+2 month'),
            'end_date' => new \DateTime('+7 months'),
            'dates_are_strict' => true,
            'description' => '',
            'probability' => 'definite',
            'state' => 'Ja',
        ])->clusterParticipations()->create([
            'cluster_id' => $cluster->id,
            'cluster_name' => $cluster->name,
            'profile_id' => $cluster->loadProfiles()->get()->toArray()[1]['id'],
            'profile_name' => $cluster->loadProfiles()->get()->toArray()[1]['name'],
            'competence_id' => 2,
            'competence_name' => 'Applikations-Design',
            'requirement_id' => (Integer) $requirement->id,
            'load' => rand(10,65)
        ],[
            'cluster_id' => $cluster->id,
            'cluster_name' => $cluster->name,
            'profile_id' => $cluster->loadProfiles()->get()->toArray()[2]['id'],
            'profile_name' => $cluster->loadProfiles()->get()->toArray()[2]['name'],
            'competence_id' => 2,
            'competence_name' => 'Applikations-Entwicklung',
            'requirement_id' => (Integer) $requirement->id,
            'load' => rand(10,65)
        ]);

        $requirement->phases()->create([
            'title' => 'Klärungsworkshops',
            'start_date' => new \DateTime('+6 months'),
            'end_date' => new \DateTime('+7 months'),
            'dates_are_strict' => true,
            'description' => '',
            'probability' => 'potential',
            'state' => 'Ja',
        ])->clusterParticipations()->create([
            'cluster_id' => $cluster->id,
            'cluster_name' => $cluster->name,
            'profile_id' => $cluster->loadProfiles()->get()->toArray()[2]['id'],
            'profile_name' => $cluster->loadProfiles()->get()->toArray()[2]['name'],
            'competence_id' => 3,
            'competence_name' => 'Testing & Dokumentation',
            'requirement_id' => (Integer) $requirement->id,
            'load' => 30
        ]);

        $requirement->phases()->create([
            'title' => 'Zweites Angebot',
            'start_date' => new \DateTime('+7 months'),
            'end_date' => new \DateTime('+8 months'),
            'dates_are_strict' => true,
            'description' => '',
            'probability' => 'potential',
            'state' => 'Ja',
        ])->clusterParticipations()->create([
            'cluster_id' => $cluster->id,
            'cluster_name' => $cluster->name,
            'profile_id' => $cluster->loadProfiles()->get()->toArray()[2]['id'],
            'profile_name' => $cluster->loadProfiles()->get()->toArray()[2]['name'],
            'competence_id' => 3,
            'competence_name' => 'Testing & Dokumentation',
            'requirement_id' => (Integer) $requirement->id,
            'load' => 30
        ]);

        $requirement->phases()->create([
            'title' => 'BAFO',
            'start_date' => new \DateTime('+8 months'),
            'end_date' => new \DateTime('+9 months'),
            'dates_are_strict' => true,
            'description' => '',
            'probability' => 'potential',
            'state' => 'Ja',
        ])->clusterParticipations()->create([
            'cluster_id' => $cluster->id,
            'cluster_name' => $cluster->name,
            'profile_id' => $cluster->loadProfiles()->get()->toArray()[2]['id'],
            'profile_name' => $cluster->loadProfiles()->get()->toArray()[2]['name'],
            'competence_id' => 3,
            'competence_name' => 'Testing & Dokumentation',
            'requirement_id' => (Integer) $requirement->id,
            'load' => 30
        ]);*/
    }
}
