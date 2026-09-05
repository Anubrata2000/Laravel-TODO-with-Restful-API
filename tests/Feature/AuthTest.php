<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase {
    use RefreshDatabase;

    public function test_user_can_register_successfully() {
        $response = $this->postJson( '/api/register', [
            'name'                  => 'John Doe',
            'email'                 => 'john@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ] );

        $response->assertStatus( 201 )
            ->assertJsonStructure( [
                'status_code',
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email'],
                    'token',
                ],
            ] );

        $this->assertDatabaseHas( 'users', [
            'email' => 'john@example.com',
        ] );
    }

    public function test_registration_validation_fails_for_duplicate_email() {
        User::factory()->create( ['email' => 'existing@example.com'] );

        $response = $this->postJson( '/api/register', [
            'name'     => 'Jane Doe',
            'email'    => 'existing@example.com',
            'password' => 'password123',
        ] );

        $response->assertStatus( 422 )
            ->assertJsonValidationErrors( ['email'] );
    }

    public function test_user_can_login_with_valid_credentials() {
        $user = User::factory()->create( [
            'email'    => 'jane@example.com',
            'password' => 'secret123',
        ] );

        $response = $this->postJson( '/api/login', [
            'email'    => 'jane@example.com',
            'password' => 'secret123',
        ] );

        $response->assertStatus( 200 )
            ->assertJsonStructure( [
                'status_code',
                'message',
                'data' => [
                    'user',
                    'token',
                ],
            ] );
    }

    public function test_user_cannot_login_with_invalid_password() {
        User::factory()->create( [
            'email'    => 'jane@example.com',
            'password' => 'secret123',
        ] );

        $response = $this->postJson( '/api/login', [
            'email'    => 'jane@example.com',
            'password' => 'wrongpassword',
        ] );

        $response->assertStatus( 401 );
    }

    public function test_authenticated_user_can_retrieve_profile() {
        $user = User::factory()->create();

        $response = $this->actingAs( $user, 'sanctum' )
            ->getJson( '/api/user/profile' );

        $response->assertStatus( 200 )
            ->assertJsonPath( 'data.email', $user->email );
    }

    public function test_authenticated_user_can_update_profile() {
        $user = User::factory()->create();

        $response = $this->actingAs( $user, 'sanctum' )
            ->putJson( '/api/user/profile', [
                'name' => 'Updated Name',
            ] );

        $response->assertStatus( 200 )
            ->assertJsonPath( 'data.name', 'Updated Name' );

        $this->assertDatabaseHas( 'users', [
            'id'   => $user->id,
            'name' => 'Updated Name',
        ] );
    }

    public function test_authenticated_user_can_logout() {
        $user  = User::factory()->create();
        $token = $user->createToken( 'auth_token' )->plainTextToken;

        $response = $this->withHeader( 'Authorization', 'Bearer ' . $token )
            ->postJson( '/api/logout' );

        $response->assertStatus( 200 );
        $this->assertCount( 0, $user->tokens );
    }
}
