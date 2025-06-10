<?php

namespace App\Console\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Console\GeneratorCommand;

class ServiceGenerator extends GeneratorCommand
{
    protected $description = 'Create a new service class';
    protected $name = 'make:service';
    protected $type = 'Service';

    protected function getStub()
    {
        $this->validate();

        if ($this->option('template')) {
            return 'stubs/service.template.stub';
        } else {
            return 'stubs/service.stub';
        }
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Services';
    }

    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        if (!$this->option('template')) {
            $stub = str_replace(['public function', 'protected function', 'private function'], '// function', $stub);
        }

        $modelName = $this->option('model');
        $repositoryName = $this->option('repository');
        $serviceName = class_basename($name);

        $stub = str_replace('{{ ModelClass }}', $modelName, $stub);
        $stub = str_replace('{{ modelVariable }}', lcfirst($modelName), $stub);
        $stub = str_replace('{{ RepositoryClass }}', $repositoryName, $stub);
        $stub = str_replace('{{ ServiceClass }}', $serviceName, $stub);

        return $stub;
    }

    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_REQUIRED, 'Model class name'],
            ['repository', 'r', InputOption::VALUE_REQUIRED, 'Repository class name'],
            ['template', 't', InputOption::VALUE_NONE, 'Generate service class with methods'],
        ];
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the service class.'],
        ];
    }

    protected function validate()
    {
        if ($this->option('template') && (!$this->option('model') || !$this->option('repository'))) {
            throw new \InvalidArgumentException("The options --model and --rrepository are required when using the --template option.");
        }
    }
}
