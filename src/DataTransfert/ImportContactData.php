<?php

declare(strict_types=1);

namespace App\DataTransfert;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

final class ImportContactData
{
    public function __construct(
        #[Assert\File(mimeTypes: ['text/x-vcard', 'text/vcard'])]
        public ?File $file = null
    ) {
    }
}
