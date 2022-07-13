<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataTransfert\FavoriteUpdateData;
use App\Entity\Contact;
use App\Entity\User;
use App\Form\FavoriteUpdateType;
use App\Repository\ContactRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class FavoriteController extends AbstractController
{
    #[Route('/favorites', name: 'app_favorite_index', methods: ['GET'])]
    public function index(Request $request, PaginatorInterface $paginator, ContactRepository $repository): Response
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

    #[Route('/favorites/update', name: 'app_favorite_update', methods: ['GET', 'POST'])]
    public function update(Request $request, ContactRepository $repository): Response
    {
        $data = new FavoriteUpdateData();
        $form = $this->createForm(FavoriteUpdateType::class, $data)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data->contacts->map(function (Contact $contact) use ($repository) {
                $contact->setIsFavorite(true);
                $repository->add($contact, true);
            });

            $this->addFlash('success', 'liste de contact favoris mise à jour !');
            return $this->redirectToRoute('app_favorite_index');
        }

        return $this->renderForm(
            view: 'domain/contact/favorite_update.html.twig',
            parameters: [
                'form' => $form
            ]
        );
    }
}
