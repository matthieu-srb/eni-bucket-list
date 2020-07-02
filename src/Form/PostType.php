<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Post;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'We have 100000 users! Thanks!'
                ]
            ])
            ->add('content')
            ->add('author', EntityType::class, [
                'class' => Author::class,
                //'choice_label' => 'fullname'
                'choice_label' => function(Author $author){
                    return $author->getFirstname() . " " . $author->getLastname();
                }
            ])
            ->add('isFeatured', null, [
                'label' => 'Show at the top?'
            ])
            ->add('button', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
