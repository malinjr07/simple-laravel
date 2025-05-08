<?php

namespace App\Swagger;


class BookSwagger
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
    public function store()
    {
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
    public function show()
    {
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
    public function update()
    {
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
    public function destroy()
    {
    }
}
