<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\Tenant\TaskRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\Task;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Exclude
     * @var array
     */
    private $exclude = ['.', '..', 'TenantCommand.php'];

    /**
     * Path
     * @var string
     */
    private $path = "App\Console\Commands\\";

    public function index()
    {
        return view('tenant.task.index');
    }

    public function tables()
    {
        return Task::all();
    }

    public function listsCommand()
    {
        return collect(array_diff(scandir(app_path('Console/Commands')), $this->exclude))->map(function ($fileCommand) {
            $name = explode('.', $fileCommand)[0];

            return [
                'name' => $name,
                'class' => "{$this->path}{$name}"
            ];
        });
    }

    public function store(TaskRequest $request)
    {
        try {
            $task = Task::create([
                'class' => $request->class,
                'execution_time' => Carbon::parse($request->execution_time)->format('H:i:s'),
            ]);

            return [
                'success' => true,
                'message' => 'Se registró la tarea con éxito.'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return [
            'success' => true,
            'message' => 'Se eliminó la tarea con éxito.'
        ];
    }
}
