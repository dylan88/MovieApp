<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Movie;
use Doctrine\ORM\EntityRepository;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('img_url', UrlType::class)
            ->add('country', ChoiceType::class, ['choices' => [
                'England' => 'England',
                'France' => 'France',
                'USA' => 'USA'
            ]])
            ->add('age', IntegerType::class, [
                'attr' => [
                    'min' => 0
                ]
            ])
            ->add('description', TextareaType::class, ['attr' => ['cols'=>5]])
            ->add('price', MoneyType::class)
            ->add('genres', EntityType::class, [
                'class' => Genre::class,
                'query_builder' => function(EntityRepository $r) {
                    return $r->createQueryBuilder('g')->orderBy('g.name');
                },
                'multiple' => true
            ])
            ->add('add', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
