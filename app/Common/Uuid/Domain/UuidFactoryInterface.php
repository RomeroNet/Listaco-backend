<?php

namespace App\Common\Uuid\Domain;

interface UuidFactoryInterface
{
    public function generate(): string;
}
