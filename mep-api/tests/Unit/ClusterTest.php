<?php

namespace Tests\Unit;

use App\Models\Competence;
use App\Models\EmployeeChange;
use App\Models\LoadProfile;
use App\Models\ProfileChange;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Mockery;
use App\Models\Cluster;
use function PHPUnit\Framework\assertEquals;

class ClusterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_clusters_get()
    {
        Cluster::factory()->create();
        Cluster::factory()->create();
        $response = $this->getJson(route('clusters'));
        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data')
                ->whereType('data', 'array')
        );
        $this->assertNotEmpty($response['data']);
    }

    public function test_clusters_profiles_get()
    {
        $cluster = Cluster::factory()
            ->has(LoadProfile::factory()->count(3))
            ->create();

        $response = $this->getJson(route('clusters.profiles', ['clusterId' => $cluster->id]));
        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('data')
                ->whereType('data', 'array')
            );
        $this->assertNotEmpty($response['data']);
    }

    public function test_clusters_profile_get()
    {
        $cluster = Cluster::factory()->create();
        $profile = LoadProfile::factory()
            ->for($cluster)
            ->create();

        $response = $this->getJson(route('clusters.profile', ['clusterId' => $cluster->id,'profileId' => $profile->id]));
        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('data')
                ->whereType('data', 'array')
            );
        $this->assertNotEmpty($response['data']);
    }

    public function test_clusters_profiles_change_get()
    {
        $cluster = Cluster::factory()->create();
        $profile = LoadProfile::factory()
            ->for($cluster)
            ->create();
        $change = ProfileChange::factory()
            ->for($profile, 'loadProfiles')
            ->create();
        $response = $this->getJson(route('clusters.profiles.change', ['clusterId' => $cluster->id,'profileId' => $profile->id,'changeId' => $change->id]));
        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('data')
                ->whereType('data', 'array')
            );
        $this->assertNotEmpty($response['data']);
    }

    public function test_cluster_get()
    {
        $cluster = Cluster::factory()->create();
        $competence = Competence::factory()->create();
        $profile = LoadProfile::factory()
            ->for($cluster)
            ->hasAttached($competence)
            ->create();
        $response = $this->getJson(route('cluster', ['clusterId' => $cluster->id]));

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['data', 'data.load_profiles'])
                ->whereType('data', 'array')
                ->whereType('data.load_profiles', 'array')
            );
        $this->assertNotEmpty($response['data']);
        $this->assertNotEmpty($response['data']['load_profiles']);

        foreach ($response['data']['load_profiles'] as $element) {
            $this->assertTrue(key_exists('competences', $element));
            $this->assertNotEmpty($element['competences']);
        }

    }

    public function test_clusters_profiles_list_get()
    {
        $cluster = Cluster::factory()->create();
        $competence = Competence::factory()->create();
        $profile1 = LoadProfile::factory()
            ->for($cluster)
            ->hasAttached($competence)
            ->create();
        $profile2 = LoadProfile::factory()
            ->for($cluster)
            ->hasAttached($competence)
            ->create();
        $profile3 = LoadProfile::factory()
            ->for($cluster)
            ->hasAttached($competence)
            ->create();
        $response = $this->getJson(route('clusters.profiles.list'));
        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['data', 'data.data'])
                ->whereType('data', 'array')
                ->whereType('data.data', 'array')
            );
        $this->assertNotEmpty($response['data']);
        $this->assertNotEmpty($response['data']['data']);

        foreach ($response['data']['data'] as $element) {
            $this->assertTrue(key_exists('cluster', $element));
            $this->assertNotEmpty($element['cluster']);
            $this->assertTrue(key_exists('competences', $element));
            $this->assertNotEmpty($element['competences']);
        }
    }

    public function test_clusters_profiles_chart_get()
    {
        $cluster = Cluster::factory()->create();
        $profile = LoadProfile::factory()
            ->for($cluster)
            ->create();
        $change1 = ProfileChange::factory()
            ->for($profile, 'loadProfiles')
            ->create();
        $change2 = ProfileChange::factory()
            ->for($profile, 'loadProfiles')
            ->create();
        $change3 = ProfileChange::factory()
            ->for($profile, 'loadProfiles')
            ->create();

        $response = $this->getJson(route('clusters.profiles.chart', ['clusterId' => $cluster->id,'profileId' => $profile->id]));
        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
            $json->hasAll(['data', 'data.'.$change1->start_date, 'data.'.$change2->start_date, 'data.'.$change3->start_date])
                ->whereType('data', 'array')
            );
        assertEquals(array_key_exists($change1->start_date, $response['data']), true);
        assertEquals(array_key_exists($change2->start_date, $response['data']), true);
        assertEquals(array_key_exists($change3->start_date, $response['data']), true);
    }

    public function test_clusters_update_post()
    {
        $createBody = ['name' => 'testClusterCreate', 'description' => 'testClusterCreateDescription'];
        $createResponse = $this->postJson(route('clusters.update'), $createBody);
        $createResponse
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('cluster_id')
                ->whereType('cluster_id', 'integer')
            );

        $updateBody = ['cluster_id' => $createResponse['cluster_id'], 'name' => 'testClusterUpdate', 'description' => 'testClusterUpdateDescription'];
        $updateResponse = $this->postJson(route('clusters.update'), $updateBody);
        $updateResponse
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('cluster_id')
                ->whereType('cluster_id', 'integer')
            );
        assertEquals($createResponse['cluster_id'], $updateResponse['cluster_id']);
    }

    public function test_clusters_profiles_update_post()
    {
        $cluster = Cluster::factory()->create();
        $createBody = [
            'cluster_id' => $cluster->id,
            'description' => 'profileCreateDescription',
            'name' => 'profileCreateName',
            'comprehensive_load' => 25,
            'base_load' => 25,
            'organisation_load' => 25,
            'local_load' => 25,
            'full_time_employees' => 5.75
        ];
        $createResponse = $this->postJson(route('clusters.profiles.update'), $createBody);
        $createResponse
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('profile_id')
                ->whereType('profile_id', 'integer')
            );

        $updateBody = $createBody;
        $updateBody['profile_id'] = $createResponse['profile_id'];
        $updateBody['name'] = 'profileUpdateName';
        $updateBody['description'] = 'profileUpdateDescription';
        $updateResponse = $this->postJson(route('clusters.profiles.update'), $updateBody);
        $updateResponse
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('profile_id')
                ->whereType('profile_id', 'integer')
            );
        assertEquals($createResponse['profile_id'], $updateResponse['profile_id']);
    }

    public function test_clusters_profiles_employees_update_post()
    {
        $cluster = Cluster::factory()->create();
        $profile = LoadProfile::factory()
            ->for($cluster)
            ->create();
        $createBody = [
            'profile_id' => $profile->id,
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d', strtotime("tomorrow")),
            'change' => 2.75,
            'reason' => 'changeCreateReason'
        ];
        $createResponse = $this->postJson(route('clusters.profiles.employees.update'), $createBody);
        $createResponse
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('employee_change_id')
                ->whereType('employee_change_id', 'integer')
            );

        $updateBody = $createBody;
        $updateBody['employee_change_id'] = $createResponse['employee_change_id'];
        $updateBody['reason'] = 'changeUpdateReason';
        $updateBody['change'] = -1.25;
        $updateResponse = $this->postJson(route('clusters.profiles.employees.update'), $updateBody);
        $updateResponse
            ->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('employee_change_id')
                ->whereType('employee_change_id', 'integer')
            );
        assertEquals($createResponse['employee_change_id'], $updateResponse['employee_change_id']);
    }

    public function test_clusters_delete_delete()
    {
        $cluster = Cluster::factory()->create();
        $Response = $this->deleteJson(route('clusters.delete'), ['cluster_id' => $cluster->id]);
        $Response
            ->assertStatus(204);
    }

    public function test_clusters_profiles_delete_delete()
    {
        $cluster = Cluster::factory()->create();
        $profile = LoadProfile::factory()
            ->for($cluster)
            ->create();
        $Response = $this->deleteJson(route('clusters.profiles.delete'), ['profile_id' => $profile->id]);
        $Response
            ->assertStatus(204);
    }

    public function test_clusters_profiles_employees_delete_delete()
    {
        $cluster = Cluster::factory()->create();
        $profile = LoadProfile::factory()
            ->for($cluster)
            ->create();
        $change = ProfileChange::factory()
            ->for($profile, 'loadProfiles')
            ->create();
        $Response = $this->deleteJson(route('clusters.profiles.change.delete'), ['profile_change_id' => $change->id]);
        $Response
            ->assertStatus(204);
    }
}
