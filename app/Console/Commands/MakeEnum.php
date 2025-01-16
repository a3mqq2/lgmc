<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeEnum extends Command
{
    protected $signature = 'make:enum {name : The name of the enum}';
    protected $description = 'Create a new Enum class';

    public function handle()
    {
        $name = $this->argument('name');
        $enumPath = app_path("Enums/{$name}.php");

        if (File::exists($enumPath)) {
            $this->error("Enum {$name} already exists!");
            return Command::FAILURE;
        }

        $stub = <<<EOT
<?php

namespace App\Enums;

enum {$name}: string
{
    case Example = "example";

    public function label(): string
    {
        return match(\$this) {
            self::Example => 'Example Label',
        };
    }

    public function badgeClass(): string
    {
        return match(\$this) {
            self::Example => 'bg-primary',
        };
    }
}
EOT;

        File::ensureDirectoryExists(app_path('Enums'));
        File::put($enumPath, $stub);

        $this->info("Enum {$name} created successfully at {$enumPath}");
        return Command::SUCCESS;
    }
}
