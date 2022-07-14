<?php

declare(strict_types=1);

namespace App\Form;

use App\DataTransfert\ImportContactData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Dropzone\Form\DropzoneType;

class ImportContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('file', DropzoneType::class, [
                'label' => 'fichier (.vcf)',
                'attr' => [
                    'placeholder' => 'Glisser déposer le fichier de contacts',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ImportContactData::class,
        ]);
    }
}
