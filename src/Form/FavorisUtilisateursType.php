<?php

namespace App\Form;

use App\Entity\Budget;
use App\Entity\FavorisUtilisateurs;
use App\Entity\Genre;
use App\Entity\Styles;
use Proxies\__CG__\App\Entity\Taille;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FavorisUtilisateursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('style',EntityType::class, [
            // looks for choices from this entity
            'class' => Styles::class,
        
            // uses the Styles.nom property as the visible option string
            'choice_label' => function ($styles) {
                return $styles->getnom();
            },
            'choice_attr'  => function () {
                return ['class' =>'mx-2'  ];
            },
        ])
            ->add('Budget',EntityType::class, [
                // looks for choices from this entity
                'class' => Budget::class,
            
                // uses the Saisons.nom property as the visible option string
                'choice_label' => function ($budget) {
                    return $budget->getNom();
                },])
            ->add('tailleHaut',EntityType::class, [
                // looks for choices from this entity
                'class' => Taille::class,
            
                // uses the Saisons.nom property as the visible option string
                'choice_label' => function ($taille) {
                    return $taille->getValeur();
                },])
            ->add('tailleBas',EntityType::class, [
                // looks for choices from this entity
                'class' => Taille::class,
            
                // uses the Saisons.nom property as the visible option string
                'choice_label' => function ($taille) {
                    return $taille->getValeur();
                },])
            ->add('chaussures',EntityType::class, [
                // looks for choices from this entity
                'class' => Taille::class,
            
                // uses the Saisons.nom property as the visible option string
                'choice_label' => function ($taille) {
                    return $taille->getValeur();
                },])
            ->add('Genre',EntityType::class, [
                // looks for choices from this entity
                'class' => Genre::class,
            
                // uses the Saisons.nom property as the visible option string
                'choice_label' => function ($genre) {
                    return $genre->getDescription();
                },])
            ->add('Enregistrer',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FavorisUtilisateurs::class,
        ]);
    }
}
