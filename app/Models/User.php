<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Todo;
use App\Models\UserTodo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Ramsey\Uuid\Uuid;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;

    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot() {
        parent::boot();

        static::creating( function ( $model ) {
            if ( empty( $model->id ) ) {
                $model->id = Uuid::uuid4()->toString();
            }
        } );
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    /**
     * Get the UserTodo records associated with the user.
     */
    public function userTodos() {
        return $this->hasMany( UserTodo::class, 'user_id' );
    }

    /**
     * Get the todos associated with the user.
     */
    public function todos() {
        return $this->belongsToMany( Todo::class, 'user_todos', 'user_id', 'todo_id' );
    }
}
