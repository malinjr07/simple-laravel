<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserAuthController extends Controller
{
    function signIn(Request $request)
    {
        $fields = $request->validate([
            'emailAddress' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $fields['emailAddress'])->first();

        if (!$user || !Hash::check($fields['password'], $user->passwordHash)) {
            throw ValidationException::withMessages(['email' => ['Invalid credentials.']]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['user' => $user, 'token' => $token]);
    }

    function signUp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string',
            'lastName' => 'string',
            'emailAddress' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }


        $input = $request->all();
        $user = User::create([
            'name' => $input['firstName'] . ' ' . $input['lastName'],
            'email' => $input['emailAddress'],
            'passwordHash' => bcrypt($input['password']),
        ]);
        return response()->json([
            'message' => 'User signed up successfully',
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ], 200);
    }
}
