<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Group;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class GroupeVoter extends Voter
{
    public const MUTATION = 'GROUP_MUTATION';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::MUTATION && $subject instanceof Group;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();
        if (! $user instanceof UserInterface) {
            return false;
        }

        /** @var Group $subject */
        return $subject->getOwner() === $user;
    }
}
