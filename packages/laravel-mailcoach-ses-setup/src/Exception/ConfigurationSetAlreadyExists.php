<?php

namespace Spatie\MailcoachSesSetup\Exception;

class ConfigurationSetAlreadyExists extends \Exception
{
    public static function make(string $name)
    {
        return new static("There already exist a configuration set named `{$name}`.");
    }
}
