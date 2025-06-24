<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
// Rimuoviamo EmailVerificationRequest
use Illuminate\Http\Request; 
// Importiamo il modello User e l'evento Verified
use App\Models\User;
use Illuminate\Auth\Events\Verified;

class EmailVerificationController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request)
    {
        // 1. Troviamo l'utente usando l'ID dall'URL.
        // La rotta è /email/verify/{id}/{hash}, quindi usiamo $request->route('id').
        $user = User::find($request->route('id'));

        // Se l'utente non esiste, la richiesta non è valida.
        if (! $user) {
            abort(403, 'Link di verifica non valido: utente non trovato.');
        }

        // 2. Verifichiamo che l'utente non abbia già verificato l'email.
        if ($user->hasVerifiedEmail()) {
            return redirect(env('FRONTEND_URL') . '/login?verified=1');
        }

        // 3. Verifichiamo che l'hash nell'URL corrisponda all'hash generato per quell'utente.
        // Questa è la stessa identica logica usata da Laravel internamente.
        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            abort(403, 'Link di verifica non valido: firma non corretta.');
        }

        // 4. Se tutti i controlli passano, marchiamo l'email come verificata.
        if ($user->markEmailAsVerified()) {
            // E lanciamo l'evento 'Verified'.
            event(new Verified($user));
        }

        // 5. Infine, reindirizziamo l'utente al frontend con il messaggio di successo.
        return redirect(env('FRONTEND_URL') . '/login?verified=1');
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email già verificata.'], 400);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Link di verifica inviato!']);
    }

    public function publicResend(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        // Per sicurezza, non riveliamo se l'utente esiste o no.
        // Se l'utente esiste E non è ancora verificato, inviamo l'email.
        // In tutti gli altri casi (utente non trovato, utente già verificato),
        // non facciamo nulla ma restituiamo comunque un messaggio di successo generico.
        if ($user && ! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }
        
        // Questo messaggio generico previene la "user enumeration"
        return response()->json(['message' => "Se un account corrispondente che necessita di verifica esiste, un nuovo link è stato inviato all'indirizzo email."]);
    }
}