<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Chiamiamo direttamente il metodo di invio notifica sull'utente appena creato.
        // Questo userà il metodo personalizzato che abbiamo definito nel modello User,
        // che a sua volta invierà la nostra notifica 'CustomVerifyEmail'.
        // Questo approccio è diretto, esplicito e invierà UNA SOLA email.
        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Utente registrato con successo!'], 201);
    }

    // in AuthController.php
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Controlliamo prima se l'utente esiste
        $user = User::where('email', $request->email)->first();

        // Se l'utente esiste ma non ha verificato l'email, blocchiamo il login
        if ($user && ! $user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Per favore, verifica il tuo indirizzo email prima di accedere.'], 403); // 403 Forbidden
        }

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        $request->session()->regenerate();

        return response()->json(Auth::user());
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logout effettuato con successo.']);
    }

    public function user(Request $request)
    {
        return $request->user();
    }
}
