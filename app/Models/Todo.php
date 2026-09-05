<?php

namespace App\Models;

use App\Models\User;
use App\Models\UserTodo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

class Todo extends Model {
    use HasFactory, SoftDeletes;

    const STATUS_PENDING     = 'Pending';
    const STATUS_IN_PROGRESS = 'In Progress';
    const STATUS_COMPLETED   = 'Completed';

    const PRIORITY_LOW    = 'Low';
    const PRIORITY_MEDIUM = 'Medium';
    const PRIORITY_HIGH   = 'High';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'completed_at',
        'comments',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'due_date'     => 'date',
    ];

    protected static function boot() {
        parent::boot();

        static::creating( function ( $model ) {
            if ( empty( $model->id ) ) {
                $model->id = Uuid::uuid4()->toString();
            }
        } );
    }

    /**
     * Get the full description of the todo item.
     *
     * @return string
     */
    public function getFullDescriptionAttribute() {
        return "{$this->title}: {$this->description}";
    }

    /**
     * Get the UserTodo records associated with the todo.
     */
    public function userTodos() {
        return $this->hasMany( UserTodo::class, 'todo_id' );
    }

    /**
     * Get the users associated with the todo.
     */
    public function users() {
        return $this->belongsToMany( User::class, 'user_todos', 'todo_id', 'user_id' );
    }
}
