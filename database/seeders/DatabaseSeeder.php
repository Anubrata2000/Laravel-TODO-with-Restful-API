<?php

namespace Database\Seeders;

use App\Models\Todo;
use App\Models\User;
use App\Models\UserTodo;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        // Create demo user
        $demoUser = User::factory()->create( [
            'name'  => 'Demo User',
            'email' => 'demo@example.com',
        ] );

        // Create 5 todos for demo user
        $todos = Todo::factory( 5 )->create();
        foreach ( $todos as $todo ) {
            UserTodo::create( [
                'user_id' => $demoUser->id,
                'todo_id' => $todo->id,
            ] );
        }

        // Create 3 other users with 2 todos each
        User::factory( 3 )->create()->each( function ( $user ) {
            $userTodos = Todo::factory( 2 )->create();
            foreach ( $userTodos as $todo ) {
                UserTodo::create( [
                    'user_id' => $user->id,
                    'todo_id' => $todo->id,
                ] );
            }
        } );
    }
}
