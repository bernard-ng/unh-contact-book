<?php

declare(strict_types=1);

namespace App\DataTransfert;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class FavoriteUpdateData
{
    public function __construct(
        public readonly Collection $contacts = new ArrayCollection()
    ) {
    }
}
