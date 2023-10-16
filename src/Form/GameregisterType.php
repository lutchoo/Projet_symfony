<?php

namespace App\Form;

use App\Entity\Boardgame;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameregisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('year_release')
            ->add('nbr_players')
            ->add('age_min')
            ->add('duration')
            ->add('img')
            ->add('description')
            ->add('categorie')
            ->add('owner')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Boardgame::class,
        ]);
    }
}
