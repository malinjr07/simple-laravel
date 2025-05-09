<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;

class BookController extends Controller
{

    public function index()
    {
        $books = Book::orderBy('updated_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $books
        ]);
    }

    public function store(StoreBookRequest $request)
    {

        $bookData = [
            'title' => $request->title,
            'author' => $request->author,
            'publication_year' => $request->publication_year,
        ];

        $book = Book::create($bookData);

        return response()->json([
            'status' => 'success',
            'message' => 'Book created successfully',
            'data' => $book
        ], 201);
    }

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
            'data' => $book
        ]);
    }

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
            $bookData['title'] = $request->title;
        }

        if ($request->has('author')) {
            $bookData['author'] = $request->author;
        }

        if ($request->has('publicationYear')) {
            $bookData['publication_year'] = $request->publication_year;
        }

        $book->update($bookData);

        return response()->json([
            'status' => 'success',
            'message' => 'Book updated successfully',
            'data' => $book
        ]);
    }

    public function destroy(string $id)
    {

        $book = Book::find($id);

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
}
