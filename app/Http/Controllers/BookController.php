<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
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
     * Display the specified resource.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
