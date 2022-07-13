<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('gender')
            ->add('birthday')
            ->add('website')
            ->add('job_title')
            ->add('organization')
            ->add('note')
            ->add('department')
            ->add('name')
            ->add('surname')
            ->add('phone_numbers')
            ->add('emails')
            ->add('social_networks')
            ->add('is_favorite')
            ->add('created_at')
            ->add('updated_at')
            ->add('owner')
            ->add('groups')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
