<?php

namespace App\Http\Controllers;

use App\Models\BookStatus;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class BookStatusController extends Controller {

    use ApiResponse;

    public function getAll(Request $req) {
        $statuses = BookStatus::all();
        return $this->sendResponse($statuses, 'OK', 200);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/bookStatuses/",
     *     description="Get list or one status",
     *     security={{"bearer_token":{}}},
     *     tags={"Statuses"},
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
        $status = BookStatus::find($id);
        if($status == null)
            return $this->sendError('Not found', 404);
        return $this->sendResponse($status, 'OK', 200);
    }

    //
}
