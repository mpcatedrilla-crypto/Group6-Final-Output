<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Validate HTTP Basic Authentication credentials
     * Returns user if valid, null if invalid
     */
    public static function validateBasicAuth(Request $request): ?User
    {
        $authHeader = $request->header('Authorization');
        
        if (!$authHeader || !str_starts_with($authHeader, 'Basic ')) {
            return null;
        }
        
        // Decode base64 credentials (format: username:password)
        $base64Credentials = substr($authHeader, 6);
        $credentials = base64_decode($base64Credentials);
        
        if (!$credentials || !str_contains($credentials, ':')) {
            return null;
        }
        
        list($email, $password) = explode(':', $credentials, 2);
        
        // Validate credentials
        $user = User::where('email', $email)->first();
        
        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }
        
        return $user;
    }
    
    /**
     * Send 401 Unauthorized response with Basic Auth challenge
     */
    public static function unauthorizedResponse()
    {
        return response()->json([
            'message' => 'Unauthorized - Basic Authentication required',
            'format' => 'Authorization: Basic base64(email:password)'
        ], 401, [
            'WWW-Authenticate' => 'Basic realm="Events API"'
        ]);
    }
    
    /**
     * Test endpoint to verify Basic Auth is working
     */
    public function test(Request $request)
    {
        $user = self::validateBasicAuth($request);
        
        if (!$user) {
            return self::unauthorizedResponse();
        }
        
        return response()->json([
            'message' => 'Basic Authentication successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ],
            'auth_method' => 'HTTP Basic Authentication'
        ]);
    }
}
