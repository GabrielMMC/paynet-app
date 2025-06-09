<?php

namespace App\Console\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Console\GeneratorCommand;

class RepositoryGenerator extends GeneratorCommand
{
    protected $description = 'Create a new repository class';
    protected $name = 'make:repository';
    protected $type = 'repository';

    protected function getStub()
    {
        $this->validate();

        if ($this->option('template')) {
            return 'stubs/repository.template.stub';
        } else {
            return 'stubs/repository.stub';
        }
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Repositories';
    }

    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        if (!$this->option('template')) {
            $stub = str_replace(['public function', 'protected function', 'private function'], '// function', $stub);
        }

        $modelName = $this->option('model');
        $repositoryName = class_basename($name);

        $stub = str_replace('{{ ModelVariable }}', lcfirst($modelName), $stub);
        $stub = str_replace('{{ RepositoryClass }}', $repositoryName, $stub);
        $stub = str_replace('{{ ModelClass }}', $modelName, $stub);

        return $stub;
    }

    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_REQUIRED, 'Model class name'],
            ['template', 't', InputOption::VALUE_NONE, 'Generate repository class with methods'],
        ];
    }

    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the repository class.'],
        ];
    }

    protected function validate()
    {
        if ($this->option('template') && !$this->option('model')) {
            throw new \InvalidArgumentException("The option --model is required when using the --template option.");
        }
    }
}
