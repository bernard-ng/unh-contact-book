<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\User;
use App\Repository\ContactRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class ContactController extends AbstractController
{
    #[Route('/contacts/new', name: 'app_contact_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ContactRepository $repository): Response
    {
        return new Response();
    }

    #[Route('/contacts/{id}', name: 'app_contact_show', methods: ['GET'])]
    public function show(Contact $contact): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($contact->getOwner() !== $user) {
            throw $this->createAccessDeniedException();
        }

        return $this->render(
            view: 'domain/contact/show.html.twig',
            parameters: [
                'contact' => $contact
            ]
        );
    }
}
