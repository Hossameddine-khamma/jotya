<?php

namespace App\Form;

use App\Entity\Budget;
use App\Entity\Genre;
use App\Entity\Produits;
use App\Entity\Saisons;
use App\Entity\Styles;
use App\Entity\Taille;
use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type as t;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProduitsType extends AbstractType
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
            ->add('type',EntityType::class, [
                // looks for choices from this entity
                'class' => Type::class,
            
                // uses the Saisons.nom property as the visible option string
                'choice_label' => function ($type) {
                    return $type->getDescription();
                },
            
            ])
            ->add('taille',EntityType::class, [
                // looks for choices from this entity
                'class' => Taille::class,
            
                // uses the Saisons.nom property as the visible option string
                'choice_label' => function ($taille) {
                    return $taille->getValeur();
                },
            
            ])
            ->add('Budget',EntityType::class, [
                // looks for choices from this entity
                'class' => Budget::class,
            
                // uses the Saisons.nom property as the visible option string
                'choice_label' => function ($Budget) {
                    return $Budget->getnom();
                },
            
            ])
            ->add('marque',TextType::class,[
                'required'=>false,
                'constraints' => [
                    new NotBlank(['message'=>'veuillez saisir une valeur']),
                    new t('alpha',"La marque doit être sous forme de text"),
                ],
            ])
            ->add('prix',NumberType::class,[
                'required'=>false,
                'constraints' => [
                    new NotBlank(['message'=>'veuillez saisir une valeur']),
                ],
                'grouping'=>true,
                'invalid_message' => 'le prix doit être sous la forme suivante "00.00"',
            ])
            ->add('promotion',IntegerType::class,[
                'required' => false,
                ])
            
            ->add('etat',TextType::class,[
                'required' => false,
                'constraints' => [
                    new NotBlank(['message'=>'veuillez saisir une valeur']),
                    new t('alpha',"L'etat doit être sous forme de text"),
                ],
            ])
            ->add('styles',EntityType::class, [
                'constraints' => [
                    new NotBlank(['message'=>'Veuillez choisir une valeur']),
                    new Count(
                        $exactly=1,
                        $min=null,
                        $max=null,
                        $divisibleBy = null,
                        $exactMessage = 'vous devez choisir une seul valeur',
                        $minMessage=null,
                        $maxMessage=null,
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
                        $exactly=1,
                        $min=null,
                        $max=null,
                        $divisibleBy = null,
                        $exactMessage = 'vous devez choisir une seul valeur',
                        $minMessage=null,
                        $maxMessage=null,
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
            'data_class' => Produits::class,
        ]);
    }
}
