<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class UserRolesController extends Controller
{
    use ApiResponse;


    public function getAll(Request $req) {
        $roles = UserRole::all();
        return $this->sendResponse($roles, 'OK', 200);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/userRoles/",
     *     description="Get list or one role",
     *     security={{"bearer_token":{}}},
     *     tags={"Roles"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=false,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized.",
     *     ),
     *     @OA\SecurityScheme(
     *         scheme="bearerAuth",
     *     ),
     * )
     */
    public function get($id, Request $req) {
        $role = UserRole::find($id);
        if($role == null)
            return $this->sendError('Not found', 404);
        return $this->sendResponse($role, 'OK', 200);
    }

    //
}
