<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Contact;
use App\Entity\Group;
use App\Entity\User;
use App\Repository\ContactRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class GroupType extends AbstractType
{
    public function __construct(
        private readonly Security $security
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('contacts', EntityType::class, [
                'attr' => [
                    'is' => 'app-select-choices',
                ],
                'placeholder' => 'Choisissez un contact',
                'choice_label' => 'full_name',
                'class' => Contact::class,
                'label' => 'Contacts',
                'multiple' => true,
                'query_builder' => function (ContactRepository $repository) use ($user) {
                    return $repository->createQueryBuilder('c')
                        ->where('c.owner = :owner')
                        ->orderBy('c.created_at', 'DESC')
                        ->setParameter('owner', $user);
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Group::class,
        ]);
    }
}
