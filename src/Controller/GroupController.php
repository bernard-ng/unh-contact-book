<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Group;
use App\Entity\User;
use App\Form\GroupType;
use App\Repository\GroupRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/groups')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class GroupController extends AbstractController
{
    #[Route('', name: 'app_group_index', methods: ['GET'])]
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $data = $paginator->paginate(
            target: $user->getGroups(),
            page: $request->query->getInt('page', 1),
            limit: 20
        );

        return $this->render('domain/group/index.html.twig', [
            'data' => $data
        ]);
    }

    #[Route('/new', name: 'app_group_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GroupRepository $repository): Response
    {
        /** @var User $owner */
        $owner = $this->getUser();
        $group = new Group();
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $group->setOwner($owner);
            $repository->add($group, true);

            $this->addFlash('success', 'le groupe a été créé avec succès');
            return $this->redirectToRoute('app_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm(
            view: 'domain/group/new.html.twig',
            parameters: [
                'group' => $group,
                'form' => $form,
            ]
        );
    }

    #[Route('/{id}', name: 'app_group_show', methods: ['GET'])]
    public function show(Group $group): Response
    {
        $this->denyAccessUnlessGranted('GROUP_MUTATION', $group);

        return $this->render('domain/group/show.html.twig', [
            'data' => $group,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_group_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Group $group, GroupRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('GROUP_MUTATION', $group);
        $form = $this->createForm(GroupType::class, $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->add($group, true);

            $this->addFlash('success', 'le groupe a été modifié avec succès');
            return $this->redirectToRoute('app_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('domain/group/edit.html.twig', [
            'data' => $group,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_group_delete', methods: ['DELETE'])]
    public function delete(Request $request, Group $group, GroupRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('GROUP_MUTATION', $group);

        if ($this->isCsrfTokenValid('delete_' . $group->getId(), $request->request->get('_token'))) {
            $repository->remove($group, true);
        }

        $this->addFlash('success', 'le groupe a été supprimé avec succès');
        return $this->redirectToRoute('app_group_index', [], Response::HTTP_SEE_OTHER);
    }
}
