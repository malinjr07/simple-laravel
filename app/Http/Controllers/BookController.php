<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Book API",
 *     version="1.0.0",
 *     description="Simple CRUD API for managing books"
 * )
 */
class BookController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/books",
     *     summary="Get list of books",
     *     @OA\Response(response=200, description="Successful"),
     *     tags={"Books"}
     * )
     */
    public function index()
    {
        $books = Book::orderBy('book_updated_at', 'desc')->get();
        $transformedBooks = $books->map(function ($book) {
            return $this->transformBook($book);
        });
        return response()->json([
            'status' => 'success',
            'data' => $transformedBooks
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/books",
     *     summary="Create a new book",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","author","publication_year"},
     *             @OA\Property(property="title", type="string", example="My Book"),
     *             @OA\Property(property="author", type="string", example="Jane Doe"),
     *             @OA\Property(property="publication_year", type="integer", example=2020)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Created"),
     *     @OA\Response(response=422, description="Validation Error"),
     *     tags={"Books"}
     * )
     */
    public function store(StoreBookRequest $request)
    {

        $bookData = [
            'book_title' => $request->title,
            'book_author' => $request->author,
            'book_publication_year' => $request->publicationYear,
        ];

        $book = Book::create($bookData);

        return response()->json([
            'status' => 'success',
            'message' => 'Book created successfully',
            'data' => $this->transformBook($book)
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/books/{id}",
     *     summary="Get a single book by ID",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the book",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Book found"),
     *     @OA\Response(response=404, description="Book not found")
     * )
     */
    public function show(string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $this->transformBook($book)
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/books/{id}",
     *     summary="Update an existing book",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the book to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Updated Title"),
     *             @OA\Property(property="author", type="string", example="Updated Author"),
     *             @OA\Property(property="publication_year", type="integer", example=2023)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Book updated"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=404, description="Book not found")
     * )
     */
    public function update(UpdateBookRequest $request, string $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book not found'
            ], 404);
        }

        $bookData = [];

        if ($request->has('title')) {
            $bookData['book_title'] = $request->title;
        }

        if ($request->has('author')) {
            $bookData['book_author'] = $request->author;
        }

        if ($request->has('publicationYear')) {
            $bookData['book_publication_year'] = $request->publicationYear;
        }

        $book->update($bookData);

        return response()->json([
            'status' => 'success',
            'message' => 'Book updated successfully',
            'data' => $this->transformBook($book)
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/books/{id}",
     *     summary="Delete a book by ID",
     *     tags={"Books"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the book to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Book deleted"),
     *     @OA\Response(response=404, description="Book not found")
     * )
     */
    public function destroy(string $bookId)
    {

        $book = Book::find($bookId);

        if (!$book) {
            return response()->json([
                'status' => 'error',
                'message' => 'Book not found'
            ], 404);
        }

        $book->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Book deleted successfully'
        ], 204);
    }

    /**
     * Transform book data for API response.
     */
    private function transformBook($book)
    {
        return [
            'id' => $book->book_id,
            'title' => $book->book_title,
            'author' => $book->book_author,
            'publicationYear' => $book->book_publication_year,
            'created_at' => $book->book_created_at,
            'updated_at' => $book->book_updated_at,
        ];
    }
}
