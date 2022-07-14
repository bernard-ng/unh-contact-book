<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\User;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[Route('/contacts', name: 'app_contact_')]
final class ContactController extends AbstractController
{
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, ContactRepository $repository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $data = new Contact();
        $form = $this->createForm(ContactType::class, $data)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data->setOwner($user);
            $repository->add($data, true);

            $this->addFlash('success', 'le contact a bien été créé !');
            return $this->redirectToRoute('app_index', status: Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm(
            view: 'domain/contact/new.html.twig',
            parameters: [
                'form' => $form
            ]
        );
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Contact $contact): Response
    {
        $this->denyAccessUnlessGranted('CONTACT_MUTATION', $contact);

        return $this->render(
            view: 'domain/contact/show.html.twig',
            parameters: [
                'data' => $contact
            ]
        );
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contact $contact, ContactRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('CONTACT_MUTATION', $contact);
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->add($contact, true);

            $this->addFlash('success', 'le contact a été modifié avec succès');
            return $this->redirectToRoute(
                route: 'app_contact_show',
                parameters: [
                    'id' => $contact->getId()
                ],
                status: Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('domain/contact/edit.html.twig', [
            'data' => $contact,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, Contact $contact, ContactRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('CONTACT_MUTATION', $contact);

        if ($this->isCsrfTokenValid('delete_' . $contact->getId(), $request->request->get('_token'))) {
            $repository->remove($contact, true);
        }

        $this->addFlash('success', 'le contact a été supprimé avec succès');
        return $this->redirectToRoute('app_index', [], Response::HTTP_SEE_OTHER);
    }
}
