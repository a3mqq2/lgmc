<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeServiceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name : The name of the service class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');
        $serviceName = Str::studly($name);

        $path = app_path("Services/{$serviceName}.php");

        if (file_exists($path)) {
            $this->error("Service {$serviceName} already exists!");
            return 1;
        }

        if (! is_dir(app_path('Services'))) {
            mkdir(app_path('Services'), 0755, true);
        }

        $stub = $this->getStub();
        $stub = str_replace('{{ class }}', $serviceName, $stub);

        file_put_contents($path, $stub);

        $this->info("Service {$serviceName} created successfully.");
        return 0;
    }

    /**
     * Get the service class stub.
     *
     * @return string
     */
    protected function getStub()
    {
        return <<<EOT
        <?php

        namespace App\Services;

        class {{ class }}
        {
            //
        }
        EOT;
    }
}
