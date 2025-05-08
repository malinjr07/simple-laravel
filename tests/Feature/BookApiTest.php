<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Book;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_book()
    {
        $data = [
            'title' => 'Test Book',
            'author' => 'John Doe',
            'publicationYear' => 2020,
        ];

        $response = $this->postJson('/api/books', $data);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Book created successfully',
            ])
            ->assertJsonPath('data.title', 'Test Book')
            ->assertJsonPath('data.author', 'John Doe')
            ->assertJsonPath('data.publicationYear', 2020);

        $this->assertDatabaseHas('books', [
            'book_title' => 'Test Book',
            'book_author' => 'John Doe',
            'book_publication_year' => 2020,
        ]);
    }

    /** @test */
    public function it_validates_title_field()
    {
        $data = [
            'title' => '',
            'author' => 'John Doe',
            'publicationYear' => 2020,
        ];

        $response = $this->postJson('/api/books', $data);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    /** @test */
    public function it_validates_author_field()
    {
        $data = [
            'title' => 'Test Book',
            'author' => '',
            'publicationYear' => 2020,
        ];

        $response = $this->postJson('/api/books', $data);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['author']);
    }

    /** @test */
    public function it_validates_publication_year_field()
    {
        $data = [
            'title' => 'Test Book',
            'author' => '',
            'publicationYear' => 1490,
        ];

        $response = $this->postJson('/api/books', $data);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['publicationYear']);
    }

    /** @test */
    public function it_returns_a_list_of_books()
    {
        Book::factory()->count(3)->create();

        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_can_show_a_single_book()
    {
        $book = Book::factory()->create();

        $response = $this->getJson("/api/books/{$book->book_id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.title', $book->book_title);
    }

    /** @test */
    public function it_can_update_a_book()
    {
        $book = Book::factory()->create();

        $response = $this->putJson("/api/books/{$book->book_id}", [
            'title' => 'Updated Title',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Updated Title']);
    }

    /** @test */
    public function it_can_delete_a_book()
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson("/api/books/{$book->book_id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('books', ['book_id' => $book->book_id]);
    }
}
