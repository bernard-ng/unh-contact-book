<?php

declare(strict_types=1);

namespace App\UI;

use App\Twig\Sidebar\AbstractSidebar;
use App\Twig\Sidebar\SidebarBuilderInterface;
use App\Twig\Sidebar\SidebarCollection;
use App\Twig\Sidebar\Type\SidebarHeader;

final class MainSidebar extends AbstractSidebar
{
    public function build(SidebarBuilderInterface $builder): SidebarCollection
    {
        return $builder
            ->add(new SidebarHeader('Contacts'))

            ->setTranslationDomain('messages')
            ->create();
    }
}
