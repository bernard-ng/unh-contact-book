<?php

declare(strict_types=1);

namespace App\DataTransfert;

final class LoginFromData
{
    public function __construct(
        public ?string $email = null,
        public ?string $password = null,
    ) {
    }
}
