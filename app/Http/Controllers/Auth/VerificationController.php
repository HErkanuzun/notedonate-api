<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Events\Verified;

class VerificationController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        if (! URL::hasValidSignature($request)) {
            abort(403, 'Invalid or expired verification link.');
        }

        if ($user->hasVerifiedEmail()) {
            return response('Email zaten doğrulanmış.', 200)
                ->header('Content-Type', 'text/plain');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response('Mesajınız onaylanmıştır.', 200)
            ->header('Content-Type', 'text/plain');
    }
}
