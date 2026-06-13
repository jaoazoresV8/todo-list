<?php

namespace Tests\Feature;

use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_todos(): void
    {
        $todo = Todo::factory()->create([
            'title' => 'Buy groceries',
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee($todo->title);
    }

    public function test_store_creates_todo(): void
    {
        $response = $this->post('/todos', [
            'title' => 'Write report',
        ]);

        $this->assertDatabaseHas('todos', [
            'title' => 'Write report',
            'is_completed' => false,
        ]);

        $response->assertRedirect(route('todos.index'));
    }

    public function test_toggle_updates_completion_status(): void
    {
        $todo = Todo::factory()->incomplete()->create();

        $this->patch(route('todos.toggle', $todo));

        $this->assertTrue($todo->fresh()->is_completed);
    }

    public function test_destroy_soft_deletes_todo(): void
    {
        $todo = Todo::factory()->create();

        $response = $this->delete(route('todos.destroy', $todo));

        $this->assertSoftDeleted($todo);
        $response->assertRedirect(route('todos.index'));
    }
}
