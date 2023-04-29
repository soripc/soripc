<?php

namespace App\Console\Commands;

use App\Models\System\Client;
use App\Models\Tenant\Company;
use Hyn\Tenancy\Environment;
use Illuminate\Console\Command;
use App\Models\Tenant\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

class TenantProductionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant_production:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute the scheduled tasks of the tenants';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $time = Carbon::now()->format('H:i') . ':00';

        $clients = Client::query()
            ->select('id', 'name', 'hostname_id', 'tasks')
            ->with('hostname:id,website_id', 'hostname.website')
            ->whereJsonContains('tasks', ['execution_time' => $time])
            ->where('soap_type_id', '02')
            ->get();
        foreach ($clients as $client) {
            $this->info('c: ' . $client->name);
            $tenancy = app(Environment::class);
            $tenancy->tenant($client->hostname->website);
            foreach ($client->tasks as $task) {
                if ($task['execution_time'] === $time) {
                    $c = Company::query()->select('name')->first();
                    $this->info('t: ' . $c->name);
                    try {
                        Artisan::call($task['class']);
                        $output = Artisan::output();
                    } catch (\Exception $e) {
                        $output = $e->getMessage();
                    }

                    Task::query()
                        ->where('id', $task['id'])
                        ->update([
                            'output' => $output
                        ]);
                }
            }

        }
//        foreach (Task::where('execution_time', Carbon::now()->format('H:i') . ':00')->get() as $task) {
//            try {
//                Artisan::call($task->class);
//                $task->output = Artisan::output();
//                $task->save();
//            } catch (\Exception $e) {
//                $task->output = $e->getMessage();
//                $task->save();
//            }
//        };
    }
}
