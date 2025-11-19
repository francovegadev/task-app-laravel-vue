<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskDueDateSoonNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use PhpParser\Node\Stmt\TryCatch;

class CheckTasksDueDateSoon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:check-due-date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifica a los usuarios cuando una tarea está próxima a vencerse';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $limit = $now->copy()->addDays(2);

        $tasks = Task::whereBetween('due_date', [$now, $limit])
            ->whereNotNull('user_id')
            ->with('user')
            ->get();

        foreach ($tasks as $task) {
            $user = $task->user;
            if (!$user) {
                $this->error("Task {$task->id} no tiene usuario asignado.");
                continue;
            }

            Try {
                $user->notify(new TaskDueDateSoonNotification($task));
            } catch (\Exception $e) {
                $this->error("Error notificando al usuario {$user->name} para la tarea {$task->title}: " . $e->getMessage());
            }
        }

        $this->info('Comando ejecutado: Se han notificado las tareas próximas a vencerse.');
    }
}
