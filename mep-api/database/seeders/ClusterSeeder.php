<?php

namespace Database\Seeders;

use App\Models\Cluster;
use Illuminate\Database\Seeder;

class ClusterSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Customer Care Cluster
        $firstTestCluster = new Cluster();
        $firstTestCluster->name = "Test Cluster 1";
        $firstTestCluster->description = "Cluster für Unit-Test";
        $firstTestCluster->save();

        // Create a load profile
        $firstTestCluster->loadProfiles()->create([
            'name' => 'Profil 1 Cluster 1',
            'description' => 'Profil für Unit-Test',
            'organisation_load' => 5,
            'base_load' => 10,
            'local_load' => 0,
            'comprehensive_load' => 85,
        ]);

        $firstTestCluster->loadProfiles()->create([
            'name' => 'Profil 2 Cluster 1',
            'description' => 'Profil für Unit-Test',
            'organisation_load' => 5,
            'base_load' => 85,
            'local_load' => 5,
            'comprehensive_load' => 5,
        ]);

        $firstTestCluster->loadProfiles()->create([
            'name' => 'Profil 3 Cluster 1',
            'description' => 'Profil für Unit-Test',
            'organisation_load' => 10,
            'base_load' => 75,
            'local_load' => 5,
            'comprehensive_load' => 10,
        ]);

        $firstTestCluster->loadProfiles()->create([
            'name' => 'Profil 4 Cluster 1',
            'description' => 'Profil für Unit-Test',
            'organisation_load' => 10,
            'base_load' => 25,
            'local_load' => 5,
            'comprehensive_load' => 60,
        ]);

        // Datacenter Security Cluster
        $datacenterSercurity = new Cluster();
        $datacenterSercurity->name = "Test Cluster 2";
        $datacenterSercurity->description = "Cluster für Unit-Test";
        $datacenterSercurity->save();

        $datacenterSercurity->loadProfiles()->create([
            'name' => 'Profil 1 Cluster 2',
            'description' => 'Profil für Unit-Test',
            'organisation_load' => 5,
            'base_load' => 93,
            'local_load' => 1,
            'comprehensive_load' => 1,
        ]);


        // Internal Services Cluster
        $internalServices = new Cluster();
        $internalServices->name = "Test Cluster 3";
        $internalServices->description = "Cluster für Unit-Test";
        $internalServices->save();

        $internalServices->loadProfiles()->create([
            'name' => 'Profil 1 Cluster 3',
            'description' => 'Profil für Unit-Test',
            'organisation_load' => 20,
            'base_load' => 35,
            'local_load' => 15,
            'comprehensive_load' => 30,
        ]);

        $internalServices->loadProfiles()->create([
            'name' => 'Profil 2 Cluster 3',
            'description' => 'Profil für Unit-Test',
            'organisation_load' => 15,
            'base_load' => 45,
            'local_load' => 30,
            'comprehensive_load' => 10,
        ]);

        $internalServices->loadProfiles()->create([
            'name' => 'Profil 3 Cluster 3',
            'description' => 'Profil für Unit-Test',
            'organisation_load' => 20,
            'base_load' => 15,
            'local_load' => 20,
            'comprehensive_load' => 45,
        ]);

        // Mainframe & Print services Cluster
        $mainFrame = new Cluster();
        $mainFrame->name = "Test Cluster 4";
        $mainFrame->description = "Cluster für Unit-Test";
        $mainFrame->save();

        $mainFrame->loadProfiles()->create([
            'name' => 'Profil 1 Cluster 4',
            'description' => 'Profil für Unit-Test',
            'organisation_load' => 8,
            'base_load' => 85,
            'local_load' => 5,
            'comprehensive_load' => 2,
        ]);

        // Service Delivery Cluster
        $serviceDelivery = new Cluster();
        $serviceDelivery->name = "Test Cluster 5";
        $serviceDelivery->description = "Cluster für Unit-Test";
        $serviceDelivery->save();

        $serviceDelivery->loadProfiles()->create([
            'name' => 'Profil 1 Cluster 5',
            'description' => 'Profil für Unit-Test',
            'organisation_load' => 36,
            'base_load' => 22,
            'local_load' => 19,
            'comprehensive_load' => 23,
        ]);

        $serviceDelivery->loadProfiles()->create([
            'name' => 'Profil 2 Cluster 5',
            'description' => 'Profil für Unit-Test',
            'organisation_load' => 36,
            'base_load' => 22,
            'local_load' => 19,
            'comprehensive_load' => 23,
        ]);

        $serviceDelivery->loadProfiles()->create([
            'name' => 'Profil 3 Cluster 5',
            'description' => 'Profil für Unit-Test',
            'organisation_load' => 49,
            'base_load' => 40,
            'local_load' => 0,
            'comprehensive_load' => 11,
        ]);

        $serviceDelivery->loadProfiles()->create([
            'name' => 'Profil 4 Cluster 5',
            'description' => 'Profil für Unit-Test',
            'organisation_load' => 28,
            'base_load' => 68,
            'local_load' => 4,
            'comprehensive_load' => 0,
        ]);

        $serviceDelivery->loadProfiles()->create([
            'name' => 'Profil 5 Cluster 5',
            'description' => 'Profil für Unit-Test',
            'organisation_load' => 20,
            'base_load' => 79,
            'local_load' => 1,
            'comprehensive_load' => 0,
        ]);

        $serviceDelivery->loadProfiles()->create([
            'name' => 'Profil 6 Cluster 5',
            'description' => 'Profil für Unit-Test',
            'organisation_load' => 32,
            'base_load' => 59,
            'local_load' => 0,
            'comprehensive_load' => 9,
        ]);

        // Workplace Cluster
        $workplace = new Cluster();
        $workplace->name = "Test Cluster 6";
        $workplace->description = "Cluster für Unit-Test";
        $workplace->save();

        $workplace->loadProfiles()->create([
            'name' => 'Profil 1 Cluster 6',
            'description' => 'Profil für Unit-Test',
            'organisation_load' => 18,
            'base_load' => 61,
            'local_load' => 5,
            'comprehensive_load' => 16,
        ]);

        $workplace->loadProfiles()->create([
            'name' => 'Profil 2 Cluster 6',
            'description' => 'Profil für Unit-Test',
            'organisation_load' => 12,
            'base_load' => 74,
            'local_load' => 5,
            'comprehensive_load' => 9,
        ]);

        $workplace->loadProfiles()->create([
            'name' => 'Profil 3 Cluster 6',
            'description' => 'Profil für Unit-Test',
            'organisation_load' => 22,
            'base_load' => 67,
            'local_load' => 4,
            'comprehensive_load' => 7,
        ]);

        $workplace->loadProfiles()->create([
            'name' => 'Profil 4 Cluster 6',
            'description' => 'Profil für Unit-Test',
            'organisation_load' => 16,
            'base_load' => 74,
            'local_load' => 0,
            'comprehensive_load' => 10,
        ]);

        $workplace->loadProfiles()->create([
            'name' => 'Profil 5 Cluster 6',
            'description' => 'Profil für Unit-Test',
            'organisation_load' => 12,
            'base_load' => 35,
            'local_load' => 5,
            'comprehensive_load' => 48,
        ]);

        $workplace->loadProfiles()->create([
            'name' => 'Profil 6 Cluster 6',
            'description' => 'Profil für Unit-Test',
            'organisation_load' => 28,
            'base_load' => 29,
            'local_load' => 29,
            'comprehensive_load' => 14,
        ]);
    }
}
