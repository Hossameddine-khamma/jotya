<?php

namespace App\Form;

use App\Entity\Budget;
use App\Entity\Ensembles;
use App\Entity\Genre;
use App\Entity\Produits;
use App\Entity\Saisons;
use App\Entity\Styles;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class EnsemblesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile', VichImageType::class, [
                'required'=>false,
                'constraints' => [
                    new NotBlank(['message'=>'veuillez choisir une image']),
                ],
                'allow_delete' => false,
            ])
            ->add('produits', CollectionType::class, [
                'entry_type' => ProduitsType::class,
                'entry_options' => [
                    'label' => false
                ],
                'by_reference' => false,
                // this allows the creation of new forms and the prototype too
                'allow_add' => true,
                // self explanatory, this one allows the form to be removed
                'allow_delete' => true
            ])
            ->add('Budget',EntityType::class, [
                // looks for choices from this entity
                'class' => Budget::class,
            
                // uses the Saisons.nom property as the visible option string
                'choice_label' => function ($Budget) {
                    return $Budget->getnom();
                },
            
            ])

            ->add('prix',NumberType::class,[
                'required'=>false,
                'constraints' => [
                    new NotBlank(['message'=>'veuillez saisir une valeur']),
                ],
                'grouping'=>true,
                'invalid_message' => 'le prix doit être sous la forme suivante "00.00"',
            ])
            ->add('styles',EntityType::class, [
                'constraints' => [
                    new NotBlank(['message'=>'Veuillez choisir une valeur']),
                    new Count(
                        $exactly=null,
                        $min=1,
                        $max=2,
                        $divisibleBy = null,
                        $exactMessage = null,
                        $minMessage='Veuillez choisir au moins 1 valeur',
                        $maxMessage='Vous pouvez pas choisir plus que 2 valeurs',
                        $divisibleByMessage = null,
                        $groups = null,
                        $payload = null
                    )
                    
                ],
                // looks for choices from this entity
                'class' => Styles::class,
            
                // uses the Styles.nom property as the visible option string
                'choice_label' => function ($styles) {
                    return $styles->getnom();
                },
                'choice_attr'  => function () {
                    return ['class' =>'mx-2'  ];
                },

                'multiple' =>true,
                'expanded'=>true
            ])
            ->add('saisons', EntityType::class, [
                'constraints' => [
                    new NotBlank(['message'=>'Veuillez choisir une valeur']),
                    new Count(
                        $exactly=null,
                        $min=1,
                        $max=2,
                        $divisibleBy = null,
                        $exactMessage = null,
                        $minMessage='Veuillez choisir au moins 1 valeur',
                        $maxMessage='Vous pouvez pas choisir plus que 2 valeurs',
                        $divisibleByMessage = null,
                        $groups = null,
                        $payload = null
                    )
                    
                ],
                // looks for choices from this entity
                'class' => Saisons::class,
            
                // uses the Saisons.nom property as the visible option string
                'choice_label' => function ($saisons) {
                    return $saisons->getnom();
                },
                'choice_attr'  => function () {
                    return ['class' =>'mx-2'  ];
                },

                'multiple' =>true,
                'expanded'=>true
            
            ])
            ->add('genre', EntityType::class, [
                'required'=>false,
                'constraints' => [
                    new NotBlank(['message'=>'veuillez choisir une valeur']),
                ],
                // looks for choices from this entity
                'class' => Genre::class,
            
                // uses the Saisons.nom property as the visible option string
                'choice_label' => function ($genre) {
                    return $genre->getdescription();
                },
                'choice_attr'  => function () {
                    return ['class' =>'mx-2'  ];
                },

                'multiple' =>false,
                'expanded'=>true
            
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ensembles::class,
        ]);
    }
}
