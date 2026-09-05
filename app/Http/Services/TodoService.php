<?php

namespace App\Http\Services;

use App\Models\Todo;
use App\Models\UserTodo;
use Illuminate\Support\Facades\Auth;

class TodoService {

    /**
     * Get query for todos scoped to the authenticated user.
     */
    protected function getUserTodosQuery() {
        $userId = Auth::id();
        return Todo::whereHas( 'userTodos', function ( $query ) use ( $userId ) {
            $query->where( 'user_id', $userId );
        } );
    }

    /**
     * Retrieve paginated todos with optional filtering and sorting, scoped to the current user.
     */
    public function getAllTodos( array $params = [] ) {
        $query = $this->getUserTodosQuery();

        if ( !empty( $params['status'] ) ) {
            $query->where( 'status', $params['status'] );
        }

        if ( !empty( $params['priority'] ) ) {
            $query->where( 'priority', $params['priority'] );
        }

        if ( !empty( $params['search'] ) ) {
            $search = $params['search'];
            $query->where( function ( $q ) use ( $search ) {
                $q->where( 'title', 'like', "%{$search}%" )
                  ->orWhere( 'description', 'like', "%{$search}%" );
            } );
        }

        if ( !empty( $params['due_date'] ) ) {
            $query->whereDate( 'due_date', $params['due_date'] );
        }

        $sortBy            = $params['sort_by'] ?? 'created_at';
        $sortOrder         = strtolower( $params['sort_order'] ?? 'desc' ) === 'asc' ? 'asc' : 'desc';
        $allowedSortFields = ['created_at', 'updated_at', 'due_date', 'title', 'priority', 'status'];

        if ( in_array( $sortBy, $allowedSortFields ) ) {
            $query->orderBy( $sortBy, $sortOrder );
        }

        $rowsPerPage = isset( $params['rowsPerPage'] ) ? (int) $params['rowsPerPage'] : 10;
        return $query->paginate( $rowsPerPage );
    }

    /**
     * Get a single todo by ID, scoped to the current user.
     */
    public function getTodoById( $id ) {
        return $this->getUserTodosQuery()->find( $id );
    }

    /**
     * Create a new todo and associate it with the current user.
     */
    public function createTodo( array $data ) {
        if ( isset( $data['status'] ) && $data['status'] === Todo::STATUS_COMPLETED && empty( $data['completed_at'] ) ) {
            $data['completed_at'] = now();
        }

        $todo = Todo::create( $data );

        UserTodo::create( [
            'user_id' => Auth::id(),
            'todo_id' => $todo->id,
        ] );

        return $todo;
    }

    /**
     * Update an existing todo, scoped to the current user.
     */
    public function updateTodo( $id, array $data ) {
        $todo = $this->getTodoById( $id );
        if ( !$todo ) {
            return null;
        }

        if ( isset( $data['status'] ) ) {
            if ( $data['status'] === Todo::STATUS_COMPLETED && empty( $todo->completed_at ) && empty( $data['completed_at'] ) ) {
                $data['completed_at'] = now();
            } elseif ( $data['status'] !== Todo::STATUS_COMPLETED ) {
                $data['completed_at'] = null;
            }
        }

        $todo->update( $data );
        return $todo->fresh();
    }

    /**
     * Delete a todo and its pivot record, scoped to the current user.
     */
    public function deleteTodo( $id ) {
        $todo = $this->getTodoById( $id );
        if ( !$todo ) {
            return false;
        }

        UserTodo::where( 'todo_id', $todo->id )->delete();
        $todo->delete();

        return true;
    }

    /**
     * Update status of a specific todo.
     */
    public function updateStatus( $id, string $status ) {
        return $this->updateTodo( $id, ['status' => $status] );
    }
}