<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Services\TodoService;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoWebController extends Controller {
    protected TodoService $todoService;

    public function __construct( TodoService $todoService ) {
        $this->todoService = $todoService;
    }

    public function index( Request $request ) {
        $user = Auth::user();

        // Calculate summary stats for the dashboard top widgets
        $userTodosQuery = Todo::whereHas( 'userTodos', function ( $q ) use ( $user ) {
            $q->where( 'user_id', $user->id );
        } );

        $totalCount      = ( clone $userTodosQuery )->count();
        $pendingCount    = ( clone $userTodosQuery )->where( 'status', Todo::STATUS_PENDING )->count();
        $inProgressCount = ( clone $userTodosQuery )->where( 'status', Todo::STATUS_IN_PROGRESS )->count();
        $completedCount  = ( clone $userTodosQuery )->where( 'status', Todo::STATUS_COMPLETED )->count();
        $highCount       = ( clone $userTodosQuery )->where( 'priority', Todo::PRIORITY_HIGH )->count();

        // Retrieve initial todos list
        $todos = $this->todoService->getAllTodos( $request->all() );

        // Ensure user has a Sanctum token for frontend AJAX calls
        $token = $user->tokens()->first()?->plainTextToken ?? $user->createToken( 'web_dashboard' )->plainTextToken;

        return view( 'todos.index', compact(
            'todos',
            'totalCount',
            'pendingCount',
            'inProgressCount',
            'completedCount',
            'highCount',
            'token'
        ) );
    }

    public function profile() {
        $user = Auth::user();
        return view( 'profile.index', compact( 'user' ) );
    }

    public function updateProfile( Request $request ) {
        $user = Auth::user();

        $data = $request->validate( [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ] );

        $updateData = [
            'name'  => $data['name'],
            'email' => $data['email'],
        ];

        if ( !empty( $data['password'] ) ) {
            $updateData['password'] = $data['password'];
        }

        $user->update( $updateData );

        return back()->with( 'success', 'Profile updated successfully!' );
    }
}
