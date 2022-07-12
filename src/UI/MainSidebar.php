<?php

declare(strict_types=1);

namespace App\UI;

use App\Twig\Sidebar\AbstractSidebar;
use App\Twig\Sidebar\SidebarBuilderInterface;
use App\Twig\Sidebar\SidebarCollection;
use App\Twig\Sidebar\Type\SidebarHeader;
use App\Twig\Sidebar\Type\SidebarLink;

final class MainSidebar extends AbstractSidebar
{
    public function build(SidebarBuilderInterface $builder): SidebarCollection
    {
        return $builder
            ->add(new SidebarHeader('Contacts'))
            ->add(new SidebarLink('app_index', 'Contacts', 'call'))
            ->add(new SidebarLink('app_contact_favorite', 'Favoris', 'star'))
            ->add(new SidebarLink('app_register', 'Groupes', 'users'))

            ->setTranslationDomain('messages')
            ->create();
    }
}
