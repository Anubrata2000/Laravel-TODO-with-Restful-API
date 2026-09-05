<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthWebController extends Controller {

    public function showLoginForm() {
        if ( Auth::check() ) {
            return redirect()->route( 'web.todos.index' );
        }
        return view( 'auth.login' );
    }

    public function login( Request $request ) {
        $credentials = $request->validate( [
            'email'    => 'required|email',
            'password' => 'required|string',
        ] );

        $remember = $request->has( 'remember' );

        if ( Auth::attempt( $credentials, $remember ) ) {
            $request->session()->regenerate();
            return redirect()->intended( route( 'web.todos.index' ) )
                ->with( 'success', 'Welcome back!' );
        }

        return back()->withErrors( [
            'email' => 'The provided credentials do not match our records.',
        ] )->onlyInput( 'email' );
    }

    public function showRegisterForm() {
        if ( Auth::check() ) {
            return redirect()->route( 'web.todos.index' );
        }
        return view( 'auth.register' );
    }

    public function register( Request $request ) {
        $data = $request->validate( [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ] );

        $user = User::create( [
            'name'              => $data['name'],
            'email'             => $data['email'],
            'password'          => $data['password'],
            'email_verified_at' => Carbon::now(),
        ] );

        Auth::login( $user );
        $request->session()->regenerate();

        return redirect()->route( 'web.todos.index' )
            ->with( 'success', 'Account created successfully! Welcome to your Todo Dashboard.' );
    }

    public function logout( Request $request ) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route( 'login' )
            ->with( 'success', 'You have been logged out.' );
    }
}
