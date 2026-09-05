<?php

namespace Database\Factories;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory {
    protected $model = Todo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        $status = fake()->randomElement( [Todo::STATUS_PENDING, Todo::STATUS_IN_PROGRESS, Todo::STATUS_COMPLETED] );

        return [
            'title'        => fake()->sentence( 4 ),
            'description'  => fake()->paragraph(),
            'status'       => $status,
            'priority'     => fake()->randomElement( [Todo::PRIORITY_LOW, Todo::PRIORITY_MEDIUM, Todo::PRIORITY_HIGH] ),
            'due_date'     => fake()->optional()->dateTimeBetween( 'now', '+1 month' ),
            'completed_at' => $status === Todo::STATUS_COMPLETED ? fake()->dateTimeBetween( '-1 week', 'now' ) : null,
            'comments'     => fake()->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the todo is completed.
     */
    public function completed(): static {
        return $this->state( fn( array $attributes ) => [
            'status'       => Todo::STATUS_COMPLETED,
            'completed_at' => now(),
        ] );
    }

    /**
     * Indicate that the todo is pending.
     */
    public function pending(): static {
        return $this->state( fn( array $attributes ) => [
            'status'       => Todo::STATUS_PENDING,
            'completed_at' => null,
        ] );
    }
}
