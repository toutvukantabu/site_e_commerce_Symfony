<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Taper le nom du produit'
                ],
                'required' => false
            ])
            ->add('shortDescription', TextareaType::class, [
                'label' => 'description courte',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Tapez une desciption assez courte mais parlante pour le visiteur'
                ],
                'required' => false
            ])
            ->add('price', MoneyType::class, [
                'label' => 'prix du produit',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'taper le prix du produit en euro '
                ],
                'divisor'=> 100,
                'required' => false

            ])
            ->add('mainPicture', UrlType::class, [
                'label' => 'image du produit',
                'attr' => [

                    'placeholder' => 'taper une uURL d\'image '
                ]
            ])->add('category', EntityType::class, [
                'label' => 'catégorie',
                'attr' => [
                    'class' => 'form-control'
                ],
                'placeholder' => '--choisir une categorie--',
                'class' => Category::class,
                'choice_label' => fn(Category $category) => strtoupper((string) $category->getName())
            ]);

         /* Second test */

        // $builder->get('price')->addModelTransformer(new CentimesTransformer);

        /* first test */

        //     $builder->addEventListener(FormEvents::POST_SUBMIT, function( FormEvent $event){
        //        /** @var product*/
        //    $product = $event->getData();

        //    if($product->getPrice() !== null){
        //        $product->setPrice($product->getPrice() * 100 );
        //    }
        //     });


        // $builder->addEventListener(FormEvents::PRE_SET_DATA, function( FormEvent $event){

        //    $form  = $event->getForm();
        //    /** @var product*/
        //    $product = $event->getData();
        //    if($product->getPrice() !== null){

        //        $product->setPrice($product->getPrice()/100);
        //    }
        /* if ($product->getId() === null){

                $form->add('category', EntityType::class, [
                    'label' => 'catégorie',
                    'attr' => [
                        'class' => 'form-control'
                    ],
                    'placeholder' => '--choisir une categorie--',
                    'class' => Category::class,
                    'choice_label' => function (Category $category) {
                        return strtoupper($category->getName());
                    }
                ]);
               } */

        // });

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
