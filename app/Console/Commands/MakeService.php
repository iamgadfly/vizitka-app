<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Symfony\Component\Console\Input\InputOption;

class MakeService extends GeneratorCommand
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'make:service {name}';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Make service';

    protected $type = 'Service';

    protected Composer $composer;

    public function __construct(Filesystem $files, Composer $composer)
    {
        parent::__construct($files);

        $this->composer = $composer;
    }

    protected function getStub(): string
    {
        return base_path('stubs/service.stub');
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Services';
    }


    protected function buildClass($name): string
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceNamespace($stub, $name)->replaceClass($this->replaceRepository($stub), $name);
    }

    protected function replaceRepository($stub): string
    {
        $model = explode('Service', $this->argument('name'))[0] . 'Repository';

        return str_replace('{{ repository }}', $model, $stub);
    }



    protected function getOptions(): array
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if it already exists'],
        ];
    }


    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        parent::handle();
        app()->make(Composer::class)->dumpAutoloads();
    }
}
