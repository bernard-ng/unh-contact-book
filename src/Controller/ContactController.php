<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\User;
use App\Repository\ContactRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class ContactController extends AbstractController
{
    #[Route('/contacts/favorites', name: 'app_contact_favorite', methods: ['GET'])]
    public function favorite(Request $request, PaginatorInterface $paginator, ContactRepository $repository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $data = $paginator->paginate(
            target: $repository->findFavoritesForOwner($user),
            page: $request->query->getInt('page', 1),
            limit: 20
        );

        return $this->render(
            view: 'domain/contact/favorite.html.twig',
            parameters: [
                'data' => $data
            ]
        );
    }

    #[Route('/contacts/{id}', name: 'app_contact_show', methods: ['GET'])]
    public function show(Contact $contact): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($contact->getOwner() === $user) {
            return $this->render(
                view: 'domain/contact/show.html.twig',
                parameters: [
                    'contact' => $contact
                ]
            );
        }

        throw $this->createAccessDeniedException();
    }
}
