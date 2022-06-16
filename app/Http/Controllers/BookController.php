<?php

namespace App\Http\Controllers;

use App\Models\BookStatus;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{

    use ApiResponse;


    /**
     * @OA\Get(
     *     path="/api/v1/book/",
     *     description="Get all books",
     *     security={{"bearer_token":{}}},
     *     tags={"Books"},
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
    public function getAll(Request $req)
    {
        $books = Book::all();
        return $this->sendResponse($books, 'OK', 200);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/book/{id}",
     *     description="Get one book",
     *     security={{"bearer_token":{}}},
     *     tags={"Books"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
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
     *                         @OA\Property(property="data", type="object",
     *                            @OA\Property(property="book", ref="#/components/schemas/Book"), @OA\Property(property="status", ref="#/components/schemas/BookStatus"),
     *                        ),
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
    public function get($id, Request $req)
    {
        $book = Book::find($id);
        if ($book == null)
            return $this->sendError('Not found', 404);
        $status = BookStatus::find($book->status_id);
        $result = [
            "book" => $book,
            "status" => $status,
        ];
        return $this->sendResponse($result, 'OK', 200);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/book/",
     *     description="Create a new book",
     *     security={{"bearer_token":{}}},
     *     tags={"Books"},
     *     @OA\Parameter(
     *         in="query",
     *         name="name",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="contents",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
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
    public function create(Request $req)
    {
        $book = new Book();
        $book->name = $req->input('name');
        $book->contents = $req->input('contents');

        $book->save();

        return $this->sendResponse($book, 'Created', 201);
    }

    /**
     * @OA\Delete (
     *     path="/api/v1/book/",
     *     description="Delete a book",
     *     security={{"bearer_token":{}}},
     *     tags={"Books"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
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
     *     @OA\SecurityScheme(
     *         scheme="bearerAuth",
     *     ),
     * )
     */
    public function delete($id, Request $req)
    {
        $book = Book::find($id);
        if ($book == null)
            return $this->sendError('Not found', 404);
        $book->delete();
        return $this->sendEmptyResponse('OK', 200);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/book/",
     *     description="Edit a book",
     *     security={{"bearer_token":{}}},
     *     tags={"Books"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="name",
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="contents",
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         in="query",
     *         name="status",
     *         required=false,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad request",
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
    public function edit($id, Request $req)
    {
        $book = Book::find($id);
        if ($book == null)
            return $this->sendError('Not found', 404);
        if ($book->name != $req->input('name') and $req->input('name') != null)
            $book->name = $req->input('name');
        if ($book->contents != $req->input('contents') and $req->input('contents') != null)
            $book->contents = $req->input('contents');
        if ($req->input('status') != null) {
            $status = BookStatus::find($req->input('status'));
            if ($status == null)
                return $this->sendError('Bad request', 400, ['Status invalid']);
            if ($book->status_id != $status->id)
                $book->status_id = $status->id;
        }
        $book->save();
        return $this->sendResponse($book, 'OK', 200);
    }

}
