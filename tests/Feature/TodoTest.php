<?php

namespace Tests\Feature;

use App\Models\Todo;
use App\Models\User;
use App\Models\UserTodo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoTest extends TestCase {
    use RefreshDatabase;

    protected User $user;
    protected User $otherUser;

    protected function setUp(): void {
        parent::setUp();
        $this->user      = User::factory()->create();
        $this->otherUser = User::factory()->create();
    }

    public function test_unauthenticated_user_cannot_access_todos() {
        $response = $this->getJson( '/api/todos' );
        $response->assertStatus( 401 );
    }

    public function test_user_can_create_todo() {
        $response = $this->actingAs( $this->user, 'sanctum' )
            ->postJson( '/api/todos', [
                'title'       => 'Buy Groceries',
                'description' => 'Milk, Eggs, Bread',
                'status'      => 'Pending',
                'priority'    => 'High',
                'due_date'    => '2026-09-10',
            ] );

        $response->assertStatus( 201 )
            ->assertJsonPath( 'data.title', 'Buy Groceries' );

        $todoId = $response->json( 'data.id' );

        $this->assertDatabaseHas( 'todos', [
            'id'    => $todoId,
            'title' => 'Buy Groceries',
        ] );

        $this->assertDatabaseHas( 'user_todos', [
            'user_id' => $this->user->id,
            'todo_id' => $todoId,
        ] );
    }

    public function test_user_can_only_see_their_own_todos() {
        // Create todo for user 1
        $todo1 = Todo::factory()->create( ['title' => 'User 1 Todo'] );
        UserTodo::create( ['user_id' => $this->user->id, 'todo_id' => $todo1->id] );

        // Create todo for user 2
        $todo2 = Todo::factory()->create( ['title' => 'User 2 Todo'] );
        UserTodo::create( ['user_id' => $this->otherUser->id, 'todo_id' => $todo2->id] );

        $response = $this->actingAs( $this->user, 'sanctum' )
            ->getJson( '/api/todos' );

        $response->assertStatus( 200 );
        $data = $response->json( 'data.data' );

        $this->assertCount( 1, $data );
        $this->assertEquals( 'User 1 Todo', $data[0]['title'] );
    }

    public function test_user_cannot_view_another_users_todo_by_id() {
        $todo = Todo::factory()->create();
        UserTodo::create( ['user_id' => $this->otherUser->id, 'todo_id' => $todo->id] );

        $response = $this->actingAs( $this->user, 'sanctum' )
            ->getJson( "/api/todos/{$todo->id}" );

        $response->assertStatus( 404 );
    }

    public function test_user_can_filter_todos_by_status_and_priority() {
        $todoPending = Todo::factory()->create( ['status' => 'Pending', 'priority' => 'Low'] );
        UserTodo::create( ['user_id' => $this->user->id, 'todo_id' => $todoPending->id] );

        $todoCompleted = Todo::factory()->create( ['status' => 'Completed', 'priority' => 'High'] );
        UserTodo::create( ['user_id' => $this->user->id, 'todo_id' => $todoCompleted->id] );

        $response = $this->actingAs( $this->user, 'sanctum' )
            ->getJson( '/api/todos?status=Completed&priority=High' );

        $response->assertStatus( 200 );
        $data = $response->json( 'data.data' );

        $this->assertCount( 1, $data );
        $this->assertEquals( $todoCompleted->id, $data[0]['id'] );
    }

    public function test_user_can_search_todos_by_keyword() {
        $todoMatch = Todo::factory()->create( ['title' => 'Prepare Quarterly Report'] );
        UserTodo::create( ['user_id' => $this->user->id, 'todo_id' => $todoMatch->id] );

        $todoOther = Todo::factory()->create( ['title' => 'Clean Desk'] );
        UserTodo::create( ['user_id' => $this->user->id, 'todo_id' => $todoOther->id] );

        $response = $this->actingAs( $this->user, 'sanctum' )
            ->getJson( '/api/todos?search=Quarterly' );

        $response->assertStatus( 200 );
        $data = $response->json( 'data.data' );

        $this->assertCount( 1, $data );
        $this->assertEquals( 'Prepare Quarterly Report', $data[0]['title'] );
    }

    public function test_user_can_update_todo_status() {
        $todo = Todo::factory()->pending()->create();
        UserTodo::create( ['user_id' => $this->user->id, 'todo_id' => $todo->id] );

        $response = $this->actingAs( $this->user, 'sanctum' )
            ->patchJson( "/api/todos/{$todo->id}/status", [
                'status' => 'Completed',
            ] );

        $response->assertStatus( 200 )
            ->assertJsonPath( 'data.status', 'Completed' );

        $this->assertNotNull( $response->json( 'data.completed_at' ) );
    }

    public function test_user_can_delete_todo() {
        $todo = Todo::factory()->create();
        UserTodo::create( ['user_id' => $this->user->id, 'todo_id' => $todo->id] );

        $response = $this->actingAs( $this->user, 'sanctum' )
            ->deleteJson( "/api/todos/{$todo->id}" );

        $response->assertStatus( 200 );

        $this->assertSoftDeleted( 'todos', ['id' => $todo->id] );
    }
}
