<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebTodoTest extends TestCase {
    use RefreshDatabase;

    public function test_guest_can_access_login_page() {
        $response = $this->get( '/login' );
        $response->assertStatus( 200 )
            ->assertSee( 'Sign In' );
    }

    public function test_guest_can_access_register_page() {
        $response = $this->get( '/register' );
        $response->assertStatus( 200 )
            ->assertSee( 'Sign Up' );
    }

    public function test_unauthenticated_user_is_redirected_from_todos_dashboard() {
        $response = $this->get( '/todos' );
        $response->assertRedirect( '/login' );
    }

    public function test_authenticated_user_can_access_todos_dashboard() {
        $user = User::factory()->create();

        $response = $this->actingAs( $user )
            ->get( '/todos' );

        $response->assertStatus( 200 )
            ->assertSee( 'Todo Dashboard' );
    }

    public function test_authenticated_user_can_access_profile_page() {
        $user = User::factory()->create();

        $response = $this->actingAs( $user )
            ->get( '/profile' );

        $response->assertStatus( 200 )
            ->assertSee( 'User Profile' );
    }
}
