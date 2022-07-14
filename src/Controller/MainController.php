<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class MainController extends AbstractController
{
    #[Route('/')]
    public function entry(): Response
    {
        return $this->redirectToRoute('app_index');
    }

    #[Route('/contacts', name: 'app_index')]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $data = $paginator->paginate(
            target: $user->getContacts(),
            page: $request->query->getInt('page', 1),
            limit: 20
        );

        return $this->render(
            view: 'domain/contact/index.html.twig',
            parameters: [
                'data' => $data,
            ]
        );
    }
}
