<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Models\User;

/**
 * @OA\Tag(
 * name="Auth",
 * description="API Endpoints of Auth Controller"
 * )
 */

class AuthController extends BaseController
{

    /**
     * Get a JWT via given credentials.
     */

    /**
     * @OA\Post(
     * path="/api/auth/login",
     * summary="Get a JWT via given credentials",
     * tags={"Auth"},
     * @OA\RequestBody(
     *   required=true,
     *  description="Pass user credentials",
     * @OA\JsonContent(
     * @OA\Property(property="email", type="string"),
     * @OA\Property(property="password", type="string"),
     * ),
     * ),
     * @OA\Response(
     * response=200,
     * description="Token generated successfully",
     * @OA\JsonContent(
     * @OA\Property(property="token", type="string"),
     * @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     * ),
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized",
     * ),
     * ),
     * ),
     * 
     */

    public function login(Request $request)
    {

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        // validations
        $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        // check if user exists
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $token = JWTAuth::attempt($credentials);
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = [
            'token' => $token,
            'user' => JWTAuth::user()
        ];

        return response()->json($data);
    }


    /**
     * Log the user out (Invalidate the token).
     */

        /**
         * @OA\Get(
         *  path="/api/auth/logout",
         * summary="Log the user out (Invalidate the token)",
         * tags={"Auth"},
         * @OA\Response(
         * response=200,
         * description="Successfully logged out",
         * @OA\JsonContent(
         * @OA\Property(property="message", type="string"),
         * 
         * ),
         * ),
         * @OA\Response(
         * response=401,
         * description="Unauthenticated",
         * ),
         * ),
         * ),
         * 
         */
    public function logout()
    {
        JWTAuth::user()->token->invalidate();
        $data = [
            'message' => 'Successfully logged out'
        ];
        return response()->json($data);
    }


    /**
     * Refresh a token.
     */

    /**
     *  
     *  
     * @OA\Get(
     *  path="/api/auth/refresh",
     * summary="Refresh a token",
     * tags={"Auth"},
     * @OA\Response(
     * response=200,
     * description="Token refreshed successfully",
     * @OA\JsonContent(
     * @OA\Property(property="token", type="string"),
     *  
     * ),
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated",
     * ),
     * ),
     * ),
     * 
     */

    public function refresh()
    {
        return response()->json(['token' => JWTAuth::refresh()]);
    }

    /**
     * Get the authenticated User.
     */

     /**
     * @OA\Get(
     *   path="/api/auth/user",
     *  summary="Get the authenticated User",
     * tags={"Auth"},
     * @OA\Response(
     * response=200,
     * description="User retrieved successfully",
     * @OA\JsonContent(
     * @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     * 
     * ),
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthenticated",
     * ),
     * ),
     * ),
     * 
     */

    public function user()
    {
        $data = [
            'user' => JWTAuth::user()
        ];
        return response()->json($data);
    }

    /**
     * Register a User.
     */
    /**
     * @OA\Post(
     *    path="/api/auth/register",
     *   summary="Register a User",
     *  tags={"Auth"},
     *  @OA\RequestBody(
     *   required=true,
     *  description="Pass user credentials",
     * @OA\JsonContent(
     * required={"name","email","password"},
     * @OA\Property(property="name", type="string", example="John Doe"),
     * @OA\Property(property="email", type="string", example="johndoe@gmail.com"),
     * @OA\Property(property="password", type="string", example="123456"),
     * ),
     * ),
     * @OA\Response(
     * response=200,
     * description="User created successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="User created successfully"),
     * @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     * ),
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="The given data was invalid."),
     * @OA\Property(property="errors", type="object"),
     * ),
     * ),
     * @OA\Response(
     * response=500,
     * description="Server error",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Server error"),
     * ),
     * ),
     * ), 
     * 
     */

    public function register(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        // validations
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|unique:users|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        $user->save();

        $data = [
            'message' => 'User created successfully',
            'user' => $user->transformFull()
        ];
        return response()->json($data, 200);
    }
}
