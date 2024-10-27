<?php

namespace App\Infrastructure\Common\Uuid;

use App\Domain\Common\Uuid\UuidFactoryInterface;
use Ramsey\Uuid\Uuid;

readonly class RamseyUuidFactory implements UuidFactoryInterface
{
    public function __construct(
        private Uuid $ramseyUuid
    ) {
    }

    public function generate(): string
    {
        return $this->ramseyUuid->uuid4()->toString();
    }
}
