<?php

namespace jeremykenedy\LaravelBlocker\Console;

class UpdateCommand extends InstallCommand
{
    protected $signature = 'blocker:update
        {--framework= : bootstrap3, bootstrap4, bootstrap5, or tailwind}
        {--theme= : light, dark, or system}
        {--views : Publish missing views}
        {--force : Back up and replace published views}
        {--ui-kit : Run the optional Laravel UI Kit installer}';

    protected $description = 'Update Laravel Blocker settings or explicitly refresh published views';
}
