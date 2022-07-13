<?php

namespace App\Form;

use App\DataTransfert\FavoriteUpdateData;
use App\Entity\Contact;
use App\Entity\User;
use App\Repository\ContactRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class FavoriteUpdateType extends AbstractType
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
            ->add('contacts', EntityType::class, [
                'attr' => ['is' => 'app-select-choices'],
                'placeholder' => 'Choisissez un contact',
                'choice_label' => 'name',
                'class' => Contact::class,
                'required' => false,
                'label' => 'Contacts',
                'multiple' => true,
                'query_builder' => function (ContactRepository $repository) use ($user) {
                    return $repository->createQueryBuilder('c')
                        ->where('c.owner = :owner')
                        ->setParameter('owner', $user);
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FavoriteUpdateData::class
        ]);
    }
}
