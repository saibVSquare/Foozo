<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @OA\Get(
 *     path="/api/home",
 *     operationId="index",
 *     summary="Get list of users",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *     ),
 * )
 */
class HomeController extends Controller
{

    public function index()
    {
        return response()->json([
            'message' => "Done"
        ]);
    }

}
