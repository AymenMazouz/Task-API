<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TaskCRUDTest extends TestCase
{
    use DatabaseTransactions;

    public function test_user_can_create_task()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/tasks/create', [
            'titre' => 'Nouvelle tâche',
            'description' => 'Description de la tâche',
            'date_echeance' => '2024-07-01',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'msg' => 'Tâche insérée avec succès',
            ]);
    }

    // Add tests for task update, show, delete, etc.
}
