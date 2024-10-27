<?php

namespace App\Domain\Common\Uuid;

interface UuidFactoryInterface
{
    public function generate(): string;
}
