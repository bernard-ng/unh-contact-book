<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Contact;
use App\Entity\Group;
use App\Entity\User;
use App\Repository\GroupRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\UX\Dropzone\Form\DropzoneType;

class ContactType extends AbstractType
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
            ->add('surname', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('avatar_file', DropzoneType::class, [
                'label' => 'Photo de profile',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Glisser et déposer ou parcourir',
                ],
            ])
            ->add('is_favorite', CheckboxType::class, [
                'label' => 'Contact favoris ?',
                'required' => false,
            ])
            ->add('phone_numbers', TextType::class, [
                'label' => 'Numéros de téléphone',
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                    'createOnBlur' => true,
                    'delimiter' => ',',
                ],
            ])
            ->add('emails', TextType::class, [
                'label' => 'Adresses email',
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                    'createOnBlur' => true,
                    'delimiter' => ',',
                ],
                'required' => false,
            ])
            ->add('gender', ChoiceType::class, [
                'label' => 'Genre',
                'choices' => [
                    'Masculin' => 'M',
                    'Féminin' => 'F',
                    'Non Binaire' => 'O',
                ],
                'attr' => [
                    'is' => 'app-select-choices',
                ],
            ])
            ->add('birthday', DateTimeType::class, [
                'attr' => [
                    'is' => 'app-datepicker',
                ],
                'input_format' => 'Y-m-d H:i',
                'html5' => false,
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('website', UrlType::class, [
                'label' => 'Site internet',
                'required' => false,
            ])
            ->add('job_title', TextType::class, [
                'label' => 'Poste au travail',
                'required' => false,
            ])
            ->add('department', TextType::class, [
                'label' => 'Département au travail',
                'required' => false,
            ])
            ->add('organization', TextType::class, [
                'label' => 'Organisation',
                'required' => false,
            ])
            ->add('note', TextareaType::class, [
                'label' => 'Note',
                'required' => false,
                'attr' => [
                    'is' => 'app-textarea-autogrow',
                    'row' => 20,
                ],
            ])
            ->add('social_networks', TextType::class, [
                'label' => 'Lien réseaux sociaux',
                'required' => false,
                'autocomplete' => true,
                'tom_select_options' => [
                    'create' => true,
                    'createOnBlur' => true,
                    'delimiter' => ',',
                ],
            ])
            ->add('groups', EntityType::class, [
                'attr' => [
                    'is' => 'app-select-choices',
                ],
                'placeholder' => 'Choisissez un groupe',
                'choice_label' => 'name',
                'class' => Group::class,
                'required' => false,
                'label' => 'Groupes',
                'multiple' => true,
                'query_builder' => function (GroupRepository $repository) use ($user) {
                    return $repository->createQueryBuilder('c')
                        ->where('c.owner = :owner')
                        ->orderBy('c.created_at', 'DESC')
                        ->setParameter('owner', $user);
                },
            ]);

        $transformer = new CallbackTransformer(
            transform: fn (array $data) => implode(',', $data),
            reverseTransform: fn ($data) => explode(',', $data ?? '')
        );

        $builder->get('emails')->addModelTransformer($transformer);
        $builder->get('phone_numbers')->addModelTransformer($transformer);
        $builder->get('social_networks')->addModelTransformer($transformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
