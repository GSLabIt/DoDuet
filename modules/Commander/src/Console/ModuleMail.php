<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Commander\Console;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Nwidart\Modules\Commands\MailMakeCommand;

class ModuleMail extends MailMakeCommand
{
    use BladeCompiler;

    protected $name = "module:mail";

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $base_path = resource_path("stubs/modules/src/Mail");

        // generate the markdown view for the email
        $markdown = "{{\$mail_markdown}}.blade.php.blade.php";
        $filesystem = Storage::createLocalDriver([
            'driver' => 'local',
            'root' => base_path("modules/{$this->compilationData()['studly']}/src"),
        ]);
        if ($filesystem->directoryMissing("resources/views/mail")) {
            $filesystem->makeDirectory("resources/views/mail");
        }
        $compiled_filename = Blade::render(
            Str::replaceLast(".blade.php", "", $markdown),
            [
                "mail_markdown" => $this->compilationData()["snake_param"],
            ],
            true
        );
        $filesystem->put(
            "resources/views/mail/$compiled_filename",
            Blade::render(
                file_get_contents($base_path . "/$markdown"),
                [
                    ...$this->compilationData(),
                ],
                true
            )
        );

        $file = "{{\$mail}}.php.blade.php";
        return Blade::render(
            file_get_contents($base_path . "/$file"),
            [
                ...$this->compilationData(),
            ],
            true
        );
    }
}
