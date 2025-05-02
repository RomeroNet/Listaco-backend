<?php

namespace App\Common\Uuid\Infrastructure\Factory;

use App\Common\Uuid\Domain\UuidFactoryInterface;
use Ramsey\Uuid\UuidFactoryInterface as RamseyUuidFactoryInterface;

readonly class RamseyUuidFactory implements UuidFactoryInterface
{
    public function __construct(
        private RamseyUuidFactoryInterface $ramseyUuid
    ) {
    }

    public function generate(): string
    {
        return $this->ramseyUuid->uuid4()->toString();
    }
}
