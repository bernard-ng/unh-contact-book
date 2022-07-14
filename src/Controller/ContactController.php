<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataTransfert\ImportContactData;
use App\Entity\Contact;
use App\Entity\User;
use App\Form\ContactType;
use App\Form\ImportContactType;
use App\Repository\ContactRepository;
use Sabre\VObject\Component\VCard;
use Sabre\VObject\Node;
use Sabre\VObject\Splitter\VCard as SplitterVCard;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\HeaderUtils;
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
                'form' => $form,
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
                'data' => $contact,
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
                    'id' => $contact->getId(),
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

        if ($this->isCsrfTokenValid('delete_' . $contact->getId(), (string) $request->request->get('_token'))) {
            $repository->remove($contact, true);
        }

        $this->addFlash('success', 'le contact a été supprimé avec succès');
        return $this->redirectToRoute('app_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/export', name: 'export', methods: ['GET'], priority: 10)]
    public function export(): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $vcard = new VCard();

        /** @var Contact $contact */
        foreach ($user->getContacts() as $contact) {
            $vcard->add(new VCard([
                'FN' => sprintf('%s %s', $contact->getSurname(), $contact->getName()),
                'TEL' => implode(',', $contact->getPhoneNumbers()),
                'EMAIL' => implode(',', $contact->getEmails()),
                'NOTE' => $contact->getNote(),
                'ORG' => $contact->getOrganization(),
                'GENDER' => $contact->getGender(),
                'TITLE' => $contact->getJobTitle(),
                'BDAY' => $contact->getBirthday() ? $contact->getBirthday()->format('Ymd') : '',
                'N' => [$contact->getSurname(), $contact->getName()],
                'NICKNAME' => $contact->getSurname(),
                'PHOTO' => sprintf('data:image/jpeg;base64,[%s]', $this->getBase64Avatar($contact)),
            ]));
        }

        $response = new Response($vcard->serialize());
        $response->headers->set('Content-Disposition', HeaderUtils::makeDisposition(
            disposition: HeaderUtils::DISPOSITION_ATTACHMENT,
            filename: 'contacts.vcf'
        ));

        return $response;
    }

    #[Route('/import', name: 'import', methods: ['GET', 'POST'], priority: 10)]
    public function import(Request $request, ContactRepository $repository): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $data = new ImportContactData();
        $form = $this->createForm(ImportContactType::class, $data)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = fopen((string) $data->file?->getRealPath(), 'r');

            if ($file) {
                $splitter = new SplitterVCard($file);

                while ($vcard = $splitter->getNext()) {
                    $contact = (new Contact())
                        ->setOwner($user)
                        ->setName($vcard->FN ?? '')
                        ->setSurname($vcard->NIKCNAME ?? null)
                        ->setOrganization($vcard->ORG ?? '');

                    if (isset($vcard->TEL) && $vcard->TEL instanceof Node) {
                        array_map(
                            fn ($tel) => $contact->addPhoneNumber(strval($tel)),
                            $vcard->TEL->getIterator()->getArrayCopy()
                        );
                    }

                    if (isset($vcard->EMAIL) && $vcard->EMAIL instanceof Node) {
                        array_map(
                            fn ($email) => $contact->addEmail(strval($email)),
                            $vcard->EMAIL->getIterator()->getArrayCopy()
                        );
                    }

                    $repository->add($contact, true);
                }

                $this->addFlash('success', 'les contacts ont bien été importés');
            } else {
                $this->addSomethingWentWrongFlash();
            }

            return $this->redirectSeeOther('app_index');
        }

        return $this->renderForm(
            view: 'domain/contact/import.html.twig',
            parameters: [
                'form' => $form,
            ]
        );
    }

    private function getBase64Avatar(Contact $contact): string
    {
        $root = sprintf('%s/public', strvaL($this->getParameter('kernel.project_dir')));

        if ($contact->getAvatarUrl() && ! str_contains((string) $contact->getAvatarUrl(), 'fakeface.rest/face/view')) {
            return base64_encode(sprintf('%s%s', $root, $contact->getAvatarUrl()));
        }

        return base64_encode(sprintf('%s', $contact->getAvatarUrl()));
    }
}
