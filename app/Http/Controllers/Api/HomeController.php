<?php

namespace App\Http\Controllers\Api;

use App\Base\BaseModel;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

// /**
//  * @OA\Get(
//  *     path="/api/home",
//  *     operationId="index",
//  *     summary="Get list of users",
//  *     @OA\Response(
//  *         response=200,
//  *         description="Successful operation",
//  *     ),
//  * )
//  */

class HomeController extends Controller
{
    protected $user_model;
    public function __construct(User $user)
    {
        $this->user_model = new BaseModel($user);
    }

    public function index()
    {
        return response()->json([
            'message' => "Done"
        ]);
    }
}
