<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Contact;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Cropperjs\Factory\CropperInterface;
use Symfony\UX\Cropperjs\Form\CropperType;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

final class CropController extends AbstractController
{
    #[Route('contacts/{id}/_crop', name: 'app_cropper')]
    public function index(Contact $contact, UploaderHelper $uploaderHelper, CropperInterface $cropper, Request $request): Response
    {
        $filename = sprintf("%s/public%s", $this->getParameter('kernel.project_dir'), $contact->getDefaultAvatar());
        $crop = $cropper->createCrop($filename);
        $crop->setCroppedMaxSize(1000, 700);

        $form = $this->createFormBuilder(['crop' => $crop])
            ->add('crop', CropperType::class, [
                'public_url' => $uploaderHelper->asset($contact, 'avatar_file'),
                'cropper_options' => [
                    'aspectRatio' => 1,
                ],
            ])
            ->getForm()
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            file_put_contents($filename, $crop->getCroppedThumbnail(200, 150));
            $this->addFlash('success', 'photo de profile redimensionner avec succès !');

            return $this->redirectSeeOther(
                route: 'app_contact_show',
                params: ['id' => $contact->getId()]
            );
        }

        return $this->renderForm(
            view: 'domain/contact/crop.html.twig',
            parameters: [
                'form' => $form,
                'data' => $contact
            ]
        );
    }
}
