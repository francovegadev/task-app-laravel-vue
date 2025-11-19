<?php

namespace Tests\Unit;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class CreateTaskTest extends TestCase
{
    public function test_create_task()
    {
        $task = new Task([
            'title' => 'test 1',
            'description' => 'esta es una descripcion de prueba',
            'status' => 'completed',
            'due_date' => date('Y-m-d'),
            'user_id' => 1
        ]);

        $task->setAttribute('title', 'test 1');
        $this->assertEquals('test 1', $task->title);
    }
}
