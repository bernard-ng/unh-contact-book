<?php

declare(strict_types=1);

namespace App\Twig\Sidebar;

use App\Twig\Sidebar\Type\SidebarItemInterface;

/**
 * Interface SidebarBuilderInterface.
 *
 * @author bernard-ng <bernard@devscast.tech>
 */
interface SidebarBuilderInterface extends \Traversable, \Countable
{
    public function add(SidebarItemInterface $item): self;

    public function get(string $name): array;

    public function remove(string $name): self;

    public function has(string $name): bool;

    public function all(): array;

    public function create(): SidebarCollection;

    public function setTranslationDomain(string $domain): self;
}
