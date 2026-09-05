<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase {
    use RefreshDatabase;

    public function test_authenticated_user_can_list_users() {
        User::factory( 3 )->create();
        $authUser = User::factory()->create();

        $response = $this->actingAs( $authUser, 'sanctum' )
            ->getJson( '/api/users' );

        $response->assertStatus( 200 )
            ->assertJsonStructure( [
                'status_code',
                'message',
                'data' => [
                    'data',
                ],
            ] );
    }

    public function test_authenticated_user_can_get_user_by_id() {
        $authUser   = User::factory()->create();
        $targetUser = User::factory()->create(['name' => 'Target User']);

        $response = $this->actingAs( $authUser, 'sanctum' )
            ->getJson( "/api/users/{$targetUser->id}" );

        $response->assertStatus( 200 )
            ->assertJsonPath( 'data.name', 'Target User' );
    }

    public function test_authenticated_user_can_update_user_by_id() {
        $authUser   = User::factory()->create();
        $targetUser = User::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs( $authUser, 'sanctum' )
            ->putJson( "/api/users/{$targetUser->id}", [
                'name'  => 'New Name',
                'email' => $targetUser->email,
            ] );

        $response->assertStatus( 200 )
            ->assertJsonPath( 'data.name', 'New Name' );

        $this->assertDatabaseHas( 'users', [
            'id'   => $targetUser->id,
            'name' => 'New Name',
        ] );
    }

    public function test_authenticated_user_can_delete_user_by_id() {
        $authUser   = User::factory()->create();
        $targetUser = User::factory()->create();

        $response = $this->actingAs( $authUser, 'sanctum' )
            ->deleteJson( "/api/users/{$targetUser->id}" );

        $response->assertStatus( 200 );

        $this->assertDatabaseMissing( 'users', [
            'id' => $targetUser->id,
        ] );
    }
}
