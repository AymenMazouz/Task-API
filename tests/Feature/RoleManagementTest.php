<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;

class RoleManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_view_deleted_tasks()
    {
        // Create an admin user
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Access the endpoint to get deleted tasks
        $response = $this->getJson('/api/task/deleted');

        // Assert response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'deleted_tasks',
                'status',
                'msg',
            ])
            ->assertJson([
                'status' => 200,
                'msg' => 'Liste des tâches supprimées récupérée avec succès',
            ]);
    }

    public function test_non_admin_cannot_view_deleted_tasks()
    {
        // Create a regular user (non-admin)
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        // Access the endpoint to get deleted tasks
        $response = $this->getJson('/api/task/deleted');

        // Assert response
        $response->assertStatus(403)
            ->assertJson([
                'status' => 403,
                'msg' => 'Vous n\'avez pas les permissions nécessaires',
            ]);
    }
}
