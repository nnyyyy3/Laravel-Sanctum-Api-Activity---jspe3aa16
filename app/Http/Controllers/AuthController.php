<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator; // Import the Validator facade
use App\Models\User;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:8',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Validation Error',
                    'errors' => $validateUser->errors(),
                ], 401);
            }

            $newUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'status' => 'Ok',
                'message' => 'User is created successfully',
                'token' => $newUser->createToken('API-TOKEN')->plainTextToken,
            ], 200);

        } catch (\Throwable $error) {
            return response()->json([
                'status' => 'Error',
                'message' => $error->getMessage(),
            ], 500);
        }
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $credentials = Validator::make($request->only(['name', 'password']), [
                'name' => 'required|string',
                'password' => 'required|string',
            ]);

            if ($credentials->fails()) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Validation Error',
                    'errors' => $credentials->errors(),
                ], 401);
            }

            if (Auth::attempt($request->only('name', 'password'))) {
                $request->session()->regenerate();
                $token = $request->user()->createToken('API-TOKEN')->plainTextToken;
                return response()->json([
                    'status' => 'Ok',
                    'message' => 'Login Successful',
                    'token' => $token,
                ], 200);
            }

            return response()->json([
                'status' => 'Error',
                'message' => 'Invalid credentials',
            ], 401);

        } catch (\Throwable $error) {
            return response()->json([
                'status' => 'Error',
                'message' => $error->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
