<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookUserRelation;
use App\Models\User;
use App\Models\UserRole;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class BookUserRelationController extends Controller
{
    use ApiResponse;


    /**
     * @OA\Post(
     *     path="/api/v1/cabinet/",
     *     description="Create a cabinet entry",
     *     security={{"bearer_token":{}}},
     *     tags={"Cabinet"},
     *     @OA\Parameter(
     *         in="query",
     *         name="user_id",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="book_id",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="user_role_id",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized.",
     *     ),
     *      @OA\Response(
     *         response="404",
     *         description="Not found",
     *     ),
     *     @OA\SecurityScheme(
     *         scheme="bearerAuth",
     *     ),
     * )
     */
    public function create(Request $req)
    {
        $fields = $req->validate([
            'user_id' => 'required|integer',
            'book_id' => 'required|integer',
            'user_role_id' => 'required|integer',
        ]);
        $relation = new BookUserRelation();
        $user = User::find($fields['user_id']);
        if ($user == null)
            return $this->sendError('Not found', 404, ['User not found']);
        $relation->user_id = $user->id;

        $book = Book::find($fields['book_id']);
        if ($book == null)
            return $this->sendError('Not found', 404, ['Book not found']);
        $relation->book_id = $book->id;

        $userRole = UserRole::find($fields['user_role_id']);
        if ($userRole == null)
            return $this->sendError('Not found', 404, ['User role not found']);
        $relation->user_role_id = $userRole->id;

        $relation->save();
        return $this->sendResponse($relation, 'OK', 200);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/cabinet/getAllByUser",
     *     description="Get all books by user",
     *     security={{"bearer_token":{}}},
     *     tags={"Cabinet"},
     *     @OA\Parameter(
     *         in="query",
     *         name="user_id",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="user_role_id",
     *         required=false,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                   @OA\Schema(
     *                         @OA\Property(property="success", type="boolean", example=true),
     *                         @OA\Property(property="message", type="string", example="OK"),
     *                         @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Book")),
     *                   ),
     *              ),
     *         },
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized.",
     *     ),
     *      @OA\Response(
     *         response="404",
     *         description="Not found",
     *     ),
     *     @OA\SecurityScheme(
     *         scheme="bearerAuth",
     *     ),
     * )
     */
    public function getAllByUser(Request $req)
    {
        $fields = $req->validate([
            'user_id' => 'required|integer',
            'user_role_id' => 'integer',
        ]);

        $user = User::find($fields['user_id']);
        if ($user == null)
            return $this->sendError('Not found', 404, ['User not found']);

        $clauses = [['user_id', '=', $user->id]];

        if(array_key_exists('user_role_id', $fields)) {
            $userRole = UserRole::find($fields['user_role_id']);

            if ($userRole != null)
                $clauses[] = ['user_role_id', '=', $userRole->id];
        }

        $relations = BookUserRelation::where($clauses)->get();
        $books = [];
        foreach ($relations as $relation) {
            $books[] = $relation->book_id;
        }
        $books = Book::whereIn('id', $books)->get();
        return $this->sendResponse($books, 'OK', 200);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/cabinet/getCoworkersOfBook",
     *     description="Get all coworkers of book",
     *     security={{"bearer_token":{}}},
     *     tags={"Cabinet"},
     *     @OA\Parameter(
     *         in="query",
     *         name="book_id",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="user_role_id",
     *         required=false,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                   @OA\Schema(
     *                         @OA\Property(property="success", type="boolean", example=true),
     *                         @OA\Property(property="message", type="string", example="OK"),
     *                         @OA\Property(property="data", type="array", @OA\Items(
     *                            @OA\Property(property="user", ref="#/components/schemas/User"), @OA\Property(property="role", ref="#/components/schemas/UserRole"),
     *                        )),
     *                   ),
     *              ),
     *         },
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized.",
     *     ),
     *      @OA\Response(
     *         response="404",
     *         description="Not found",
     *     ),
     *     @OA\SecurityScheme(
     *         scheme="bearerAuth",
     *     ),
     * )
     */
    public function getAllOfBook(Request $req)
    {
        $fields = $req->validate([
            'book_id' => 'required|integer',
            'user_role_id' => 'integer',
        ]);

        $book = Book::find($fields['book_id']);
        if ($book == null)
            return $this->sendError('Not found', 404, ['Book not found']);

        $clauses = [['book_id', '=', $book->id]];

        if(array_key_exists('user_role_id', $fields)) {
            $userRole = UserRole::find($fields['user_role_id']);

            if ($userRole != null)
                $clauses[] = ['user_role_id', '=', $userRole->id];
        }

        $relations = BookUserRelation::where($clauses)->get();
        $result = [];
        foreach ($relations as $relation) {
            $result[] = [
                "user" => User::find($relation->user_id),
                "role" => UserRole::find($relation->user_role_id),
            ];
        }
        return $this->sendResponse($result, 'OK', 200);
    }
    //
}
