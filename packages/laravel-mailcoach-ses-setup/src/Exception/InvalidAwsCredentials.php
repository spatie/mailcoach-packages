<?php

namespace Spatie\MailcoachSesSetup\Exception;

use Aws\SesV2\Exception\SesV2Exception;
use Exception;
use Spatie\MailcoachSesSetup\MailcoachSesConfig;

class InvalidAwsCredentials extends Exception
{
    public static function make(SesV2Exception $exception, MailcoachSesConfig $config): self
    {
        $message = $exception->getAwsErrorMessage();

        if (empty($message) && str_contains($exception->message, 'Could not resolve host')) {
            $message = "You have specified an invalid region: {$config->region}.";
        };

        return new self($message, previous: $exception);
    }
}
