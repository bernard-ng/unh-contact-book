<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Contact;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ContactVoter extends Voter
{
    public const MUTATION = 'CONTACT_MUTATION';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::MUTATION && $subject instanceof Contact;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();
        if (! $user instanceof UserInterface) {
            return false;
        }

        /** @var Contact $subject */
        return $subject->getOwner() === $user;
    }
}
