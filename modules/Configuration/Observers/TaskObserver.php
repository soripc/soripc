<?php

namespace Modules\Configuration\Observers;

use App\Models\Tenant\Task;

class TaskObserver
{
    public function saved()
    {
        update_client_data([
            'tasks' => Task::query()->select('id', 'class', 'execution_time')->get()->toArray()
        ]);
    }

    public function deleted()
    {
        update_client_data([
            'tasks' => Task::query()->select('id', 'class', 'execution_time')->get()->toArray()
        ]);
    }
}
