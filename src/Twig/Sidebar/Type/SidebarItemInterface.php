<?php

declare(strict_types=1);

namespace App\Twig\Sidebar\Type;

/**
 * Interface SidebarItemInterface.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface SidebarItemInterface
{
    public function getLabel(): string;
}
