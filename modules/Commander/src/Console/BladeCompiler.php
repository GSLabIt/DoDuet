<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Commander\Console;

use Illuminate\Support\Str;

trait BladeCompiler
{
    protected function compilationData(): array
    {
        // create an array of the blade templates substitutions
        $studly = Str::studly($this->getModuleName());
        return [
            "studly" => $studly,
            "camel" => Str::camel($this->getModuleName()),
            "snake" => Str::snake($this->getModuleName()),
            "kebab" => Str::kebab($this->getModuleName()),
            "capitalized" => Str::headline($this->getModuleName()),
            "plural_snake" => Str::snake(Str::plural($this->getModuleName())),
            "namespace" => "Doinc\\Modules\\{$studly}",
            "escaped_namespace" => "Doinc\\\\Modules\\\\{$studly}",
            "studly_param" => Str::studly($this->argument($this->argumentName)),
            "camel_param" => Str::camel($this->argument($this->argumentName)),
            "snake_param" => Str::snake($this->argument($this->argumentName)),
            "kebab_param" => Str::kebab($this->argument($this->argumentName)),
            "capitalized_param" => Str::headline($this->argument($this->argumentName)),
            "plural_snake_param" => Str::snake(Str::plural($this->argument($this->argumentName))),
            "now" => now()->format("Y_m_d_his"),
            "year" => now()->year,
            "opening_tag" => "<?php"
        ];
    }
}
