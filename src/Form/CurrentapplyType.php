<?php

namespace App\Form;

use App\Entity\Currentapply;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CurrentapplyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('prenom')
            // ->add('nom')
            // ->add('date')
            ->add(
                'file',
                FileType::class,
                [
                    'label' => false,

                    'attr' => [
                        'name' => 'passport',
                        'Style' => 'margin-left: 40%'
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Currentapply::class,
        ]);
    }
}