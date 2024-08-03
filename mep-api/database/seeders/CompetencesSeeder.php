<?php

namespace Database\Seeders;

use App\Models\Cluster;
use App\Models\LoadProfile;
use App\Models\Competence;
use Illuminate\Database\Seeder;

class CompetencesSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $idList = [];
        $competences = [


            /*
            [
                'name' => 'Identity'
            ],
            [
                'name' => 'Security & Compliance'
            ],
            [
                'name' => 'Identity'
            ],
            [
                'name' => 'Infrastructure Services'
            ],
            [
                'name' => 'Windows-Server-OS'
            ], */


            /*[
                'name' => 'Applikations-Design',
            ],
            [
                'name' => 'Service-Design',
            ],
            [
                'name' => 'Applikations-Entwicklung',
            ],
            [
                'name' => 'Applikations-Bereitstellung',
            ],
            [
                'name' => 'Datenintegration',
            ],
            [
                'name' => 'Forschung',
            ],
            [
                'name' => 'Datenverarbeitungs-Design',
            ],
            [
                'name' => 'Visualisierungs-Design',
            ],
            [
                'name' => 'Service-Entwicklung',
            ],
            [
                'name' => 'Service-Bereitstellung',
            ],
            [
                'name' => 'Datentransporte & -integration',
            ],
            [
                'name' => 'Datenarchitektur & -modellierung',
            ],
            [
                'name' => 'Datenvisualisierung & Reporting',
            ]
        ];*/

            [
                'name' => 'Test ',
            ],
            [
                'name' => 'Service-Design',
            ],
            [
                'name' => 'Applikations-Entwicklung',
            ],
            [
                'name' => 'Applikations-Bereitstellung',
            ],
            [
                'name' => 'Datenintegration',
            ],
            [
                'name' => 'Forschung',
            ],
            [
                'name' => 'Datenverarbeitungs-Design',
            ],
            [
                'name' => 'Visualisierungs-Design',
            ],
            [
                'name' => 'Service-Entwicklung',
            ],
            [
                'name' => 'Service-Bereitstellung',
            ],
            [
                'name' => 'Datentransporte & -integration',
            ],
            [
                'name' => 'Datenarchitektur & -modellierung',
            ],
            [
                'name' => 'Datenvisualisierung & Reporting',
            ]
        ];

        $cluster = Cluster::where('name', 'Test Cluster 1')->first();
        foreach($competences as $competence) {
            $competenceId = Competence::firstOrCreate($competence)->id;
            array_push($idList, $competenceId);
        }
        $cluster->competences()->attach($idList);
    }
}
