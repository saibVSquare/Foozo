<?php

namespace App\Http\Controllers\Api;

use App\Base\BaseModel;
use App\Http\Controllers\Controller;
use App\Http\Dtos\UserRegisterDto;
use App\Http\Requests\UserRegister;
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


    /**
     * Signup
     * @OA\Post (
     *     path="/signup",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "name":"John",
     *                     "email":"john@test.com",
     *                     "password":"johnjohn1"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="meta", type="object",
     *                  @OA\Property(property="code", type="number", example=200),
     *                  @OA\Property(property="status", type="string", example="success"),
     *                  @OA\Property(property="message", type="string", example=null),
     *              ),
     *              @OA\Property(property="data", type="object",
     *                  @OA\Property(property="user", type="object",
     *                      @OA\Property(property="id", type="number", example=1),
     *                      @OA\Property(property="name", type="string", example="John"),
     *                      @OA\Property(property="email", type="string", example="john@test.com"),
     *                      @OA\Property(property="email_verified_at", type="string", example=null),
     *                      @OA\Property(property="updated_at", type="string", example="2022-06-28 06:06:17"),
     *                      @OA\Property(property="created_at", type="string", example="2022-06-28 06:06:17"),
     *                  ),
     *                  @OA\Property(property="access_token", type="object",
     *                      @OA\Property(property="token", type="string", example="randomtokenasfhajskfhajf398rureuuhfdshk"),
     *                      @OA\Property(property="type", type="string", example="Bearer"),
     *                      @OA\Property(property="expires_in", type="number", example=3600),
     *                  ),
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="meta", type="object",
     *                  @OA\Property(property="code", type="number", example=422),
     *                  @OA\Property(property="status", type="string", example="error"),
     *                  @OA\Property(property="message", type="object",
     *                      @OA\Property(property="email", type="array", collectionFormat="multi",
     *                        @OA\Items(
     *                          type="string",
     *                          example="The email has already been taken.",
     *                          )
     *                      ),
     *                  ),
     *              ),
     *              @OA\Property(property="data", type="object", example={}),
     *          )
     *      )
     * )
     */

    public function signup(UserRegister $request)
    {
        DB::beginTransaction();

        try {
            $UserDto = UserRegisterDto::fromRequest($request);
            $user = $this->user_model->create($UserDto->toArray());
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