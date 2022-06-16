<?php

namespace Spatie\MailcoachPostmarkSetup;

class MessageStream
{
    public function __construct(
        public string $id,
        public string $serverId,
        public string $name,
    ) {
    }
}
