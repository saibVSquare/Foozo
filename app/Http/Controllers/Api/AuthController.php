<?php

namespace App\Http\Controllers\Api;

use App\Base\BaseModel;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    /**
     * @OA\POST(
     *     path="/api/login",
     *     summary="Login",
     *     tags={"Auth"},
     *     description="Login",
     *     @OA\RequestBody(
     *     request="login",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="email", type="string", example="admin@example.com"),
     *         @OA\Property(property="password", type="string", example="123456")
     *     ),
     *      @OA\Response(response=200, description="Login"),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found")
     * )
     */

    protected $users, $logedIn_user;
    protected $user_model;
    public function __construct(User $user)
    {
        $this->users = new User();
        $this->user_model = new BaseModel($user);
    }


    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            $token = JWTAuth::attempt($credentials);


            if ($token) {
                $user = JWTAuth::setToken($token)->toUser();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successful',
                    'user' => $user,
                    'token' => $token
                ]);
            }
            throw new \Exception(__('Credentials does not match our records'));

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'false',
                'message' => $e->getMessage()
            ]);
        }
    }


    /**
     * @OA\POST(
     *     path="/api/logout",
     *     tags={"Auth"},
     *     summary="Logout",
     *     description="Logout",
     *     @OA\Response(response=200, description="Logout" ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'success' => true,
                'message' => 'Logout successful.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed. Please try again.',
            ], 500);
        }
    }


    public function signup(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => "required|email|unique:users,email"
        ]);

        DB::beginTransaction();

        try {
            $data = $request->only('first_name', 'last_name', 'email', 'password');
            if (!is_null($request->password)) {
                $data['password'] = bcrypt($request->password);
            }

            $user = $this->user_model->create($data);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e->getMessage());
        }
    }


    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ]);

        if (Hash::check($request->get('current_password'), auth()->user()->password)) {
            $result = $this->user_model->update([
                'password' => bcrypt($request->get('new_password'))
            ], auth()->user()->id);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Change password successful.'
                ]);
            } else {
                throw new \Exception('Email is not correct');
            }
        }
        throw new \Exception('Incorrect current password');
    }

    public function forgetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            $randomNumber = random_int(1000, 9999);
            $result = $this->user_model->updateWhere(
                ['email' => $request->get('email')],
                ['verification_code' => $randomNumber]
            );

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Check you email and update you password'
                ]);
            } else {
                throw new \Exception('Email is not correct');
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }


    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required',
                'password' => 'required',
                'confirm_password' => 'required|same:password',
            ]);

            $code = (int) auth()->user()->verification_code;
            if ($code == (int) $request->get('code')) {
                $result = $this->user_model->update([
                    'password' => bcrypt($request->get('password'))
                ], auth()->user()->id);

                if ($result) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Reset Password Done Successfully'
                    ]);
                } else {
                    throw new \Exception('user is not exist');
                }
            }

        } catch (\Exception $e) {
            throw new \Exception('Verification code is not correct');
        }
    }
}