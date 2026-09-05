<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller {
    protected $user;

    public function __construct() {
        $this->user = new User();
    }

    /**
     * Display a listing of the users.
     */
    public function index() {
        $users = $this->user->paginate( 10 );

        return renderJsonResponse(
            trans( 'message.user.users_retrieved_successfully' ),
            Response::HTTP_OK,
            $users
        );
    }

    /**
     * Store a newly created user in storage (Register).
     */
    public function store( UserRequest $request ) {
        $user = $this->user->create( [
            'name'              => $request->input( 'name' ),
            'email'             => $request->input( 'email' ),
            'password'          => $request->input( 'password' ),
            'email_verified_at' => Carbon::now(),
        ] );

        $token = $user->createToken( 'auth_token' )->plainTextToken;

        return renderJsonResponse(
            trans( 'message.user.created_successfully' ),
            Response::HTTP_CREATED,
            [
                'user'  => $user,
                'token' => $token,
            ]
        );
    }

    /**
     * Display the specified user.
     */
    public function show( $id ) {
        $user = $this->user->find( $id );

        if ( !$user ) {
            return renderJsonResponse(
                trans( 'message.user.not_found' ),
                Response::HTTP_NOT_FOUND
            );
        }

        return renderJsonResponse(
            trans( 'message.user.retrieved_successfully' ),
            Response::HTTP_OK,
            $user
        );
    }

    /**
     * Update the specified user in storage.
     */
    public function update( UserRequest $request, $id ) {
        $user = $this->user->find( $id );

        if ( !$user ) {
            return renderJsonResponse(
                trans( 'message.user.not_found' ),
                Response::HTTP_NOT_FOUND
            );
        }

        $data = $request->only( ['name', 'email'] );
        if ( $request->filled( 'password' ) ) {
            $data['password'] = $request->input( 'password' );
        }

        $user->update( array_filter( $data ) );

        return renderJsonResponse(
            trans( 'message.user.updated_successfully' ),
            Response::HTTP_OK,
            $user->fresh()
        );
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy( $id ) {
        $user = $this->user->find( $id );

        if ( !$user ) {
            return renderJsonResponse(
                trans( 'message.user.not_found' ),
                Response::HTTP_NOT_FOUND
            );
        }

        $user->delete();

        return renderJsonResponse(
            trans( 'message.user.deleted_successfully' ),
            Response::HTTP_OK
        );
    }

    /**
     * Login the user and return a token.
     */
    public function login( LoginRequest $request ) {
        $credentials = $request->only( 'email', 'password' );

        if ( Auth::attempt( $credentials ) ) {
            $user  = Auth::user();
            $token = $user->createToken( 'auth_token' )->plainTextToken;

            return renderJsonResponse(
                trans( 'message.user.login_successful' ),
                Response::HTTP_OK,
                [
                    'user'  => $user,
                    'token' => $token,
                ]
            );
        }

        return renderJsonResponse(
            trans( 'message.user.login_failed' ),
            Response::HTTP_UNAUTHORIZED
        );
    }

    /**
     * Log the user out and revoke current token.
     */
    public function logout( Request $request ) {
        $user = Auth::user();

        if ( $user && $request->user()?->currentAccessToken() ) {
            $request->user()->currentAccessToken()->delete();

            return renderJsonResponse(
                trans( 'message.user.logout_successful' ),
                Response::HTTP_OK
            );
        }

        return renderJsonResponse(
            trans( 'message.user.logout_failed' ),
            Response::HTTP_UNAUTHORIZED
        );
    }

    /**
     * Get authenticated user profile.
     */
    public function profile( Request $request ) {
        return renderJsonResponse(
            trans( 'message.user.retrieved_successfully' ),
            Response::HTTP_OK,
            $request->user()
        );
    }

    /**
     * Update authenticated user profile.
     */
    public function updateProfile( UserRequest $request ) {
        $user = $request->user();

        $data = $request->only( ['name', 'email'] );
        if ( $request->filled( 'password' ) ) {
            $data['password'] = $request->input( 'password' );
        }

        $user->update( array_filter( $data ) );

        return renderJsonResponse(
            trans( 'message.user.updated_successfully' ),
            Response::HTTP_OK,
            $user->fresh()
        );
    }
}
