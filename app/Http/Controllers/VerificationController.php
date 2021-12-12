<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\User;
use Illuminate\Auth\Events\Verified;

class VerificationController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = User::find($request->route('id'));

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json(['message'=>'Email Verified Successfully !!'],200);
    }

}
