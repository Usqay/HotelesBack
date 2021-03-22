<?php

namespace App\Http\Controllers;

use App\User;
use App\Traits\Utilities;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class PassportAuthController extends Controller
{
    use Utilities, HasApiTokens;
    /**
     * Registration Req
     */
    public function register(Request $request)
    {
        // $this->validate($request, [
        //     'name' => 'required|min:4',
        //     'email' => 'required|email',
        //     'password' => 'required|min:8',
        // ]);

        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => bcrypt($request->password)
        // ]);

        // $token = $user->createToken('LaravelAuthApp')->accessToken;

        // return response()->json(['token' => $token], 200);
    }

    /**
     * Login Req
     */
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        $remember = $request->remember ? $request->remember : false;

        if (auth()->attempt($data, $remember)) {
            $token = auth()->user()->createToken('Hoteles Vicodev')->accessToken;

            return $this->successResponse([
                "user" => new UserResource(auth()->user()),
                "token" => $token,
                "env" => $this->enviroments()
            ]);
        } else {
            return $this->errorResponse("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }
    }

    public function logout(Request $request){


        $accessToken = Auth::user()->token();
        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();

        $request->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return $this->successResponse(["success" => true]);
    }
}
