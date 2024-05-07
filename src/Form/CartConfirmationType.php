<?php

namespace App\Form;

use App\Entity\Purchase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartConfirmationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add(
            'fullName',
            TextType::class,
            [
                'label' => 'Nom complet',
                'attr' => [
                    'placeholder' => 'Nom complet pour la commande',
                ],
                'required' => true,
            ])
            ->add(
                'adress',
                TextareaType::class,
                [
                    'label' => 'Adresse complete',
                    'attr' => [
                        'placeholder' => 'Adresse complète',
                    ],
                    'required' => true,
                ])
            ->add(
                'postalCode',
                TextType::class,
                [
                    'label' => 'Code Postal',
                    'attr' => [
                        'placeholder' => 'Code postal',
                    ],
                    'required' => true,
                ])

            ->add(
                'city',
                TextType::class,
                [
                    'label' => 'ville',
                    'attr' => [
                        'placeholder' => 'Ville',
                    ],
                    'required' => true,
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Purchase::class,
        ]);
    }
}
