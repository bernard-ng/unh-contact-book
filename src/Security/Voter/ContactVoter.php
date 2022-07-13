<?php

namespace App\Security\Voter;

use App\Entity\Contact;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ContactVoter extends Voter
{
    public const MUTATION = 'CONTACT_MUTATION';

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute == self::MUTATION && $subject instanceof Contact;
    }

    /**
     * @param string $attribute
     * @param Contact $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        return $subject->getOwner() === $user;
    }
}
