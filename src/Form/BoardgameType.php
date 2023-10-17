<?php

namespace App\Form;
use App\Entity\Boardgame;
use PhpParser\Node\Stmt\Label;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BoardgameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('year_release')
            ->add('nbr_players')
            ->add('age_min')
            ->add('duration')
            ->add('img',FileType::class,[
                "mapped"=>false,
                "required"=>false,
                "constraints"=>[
                    new File([
                        "maxSize"=>"1024k",
                    ])
                ],
                ])
            ->add('description')
            ->add('categorie')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Boardgame::class,
        ]);
    }
}
