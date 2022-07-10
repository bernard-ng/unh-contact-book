<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataTransfert\LoginFromData;
use App\Form\LoginFromType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class LoginFormController extends AbstractController
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $utils): Response
    {
        if ($this->getUser()) {
            $this->redirectToRoute('app_index');
        }

        $error = $utils->getLastAuthenticationError();
        $command = new LoginFromData();

        if ($error !== null) {
            $command->email = $utils->getLastUsername();
            $this->addFlash('error', $this->translator->trans(
                id: $error->getMessageKey(),
                parameters: $error->getMessageData()
            ));
        }

        $form = $this->createForm(LoginFromType::class, $command);

        return $this->renderForm(
            view: 'domain/authentication/login.html.twig',
            parameters: [
                'form' => $form,
            ],
            response: $this->getResponseBasedOnFormValidationStatus($form)
        );
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
