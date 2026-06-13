<?php

namespace Tests\Unit;

use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_scopes_filter_todos(): void
    {
        Todo::factory()->completed()->create();
        Todo::factory()->incomplete()->create();

        $this->assertSame(1, Todo::completed()->count());
        $this->assertSame(1, Todo::incomplete()->count());
    }

    public function test_helper_methods_update_completion_status(): void
    {
        $todo = Todo::factory()->incomplete()->create();

        $todo->markCompleted();
        $this->assertTrue($todo->fresh()->is_completed);

        $todo->markIncomplete();
        $this->assertFalse($todo->fresh()->is_completed);

        $todo->toggleCompletion();
        $this->assertTrue($todo->fresh()->is_completed);
    }
}
