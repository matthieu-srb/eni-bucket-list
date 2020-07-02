<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Idea;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IdeaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Your idea'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Please describe it a bit'
            ])
            ->add('author', TextType::class, [
                'label' => 'Who are you? (will be published)',
                'attr' => ['placeholder' => 'Johnny']
            ])
            ->add('category', null, [
                'choice_label' => 'name',
                'query_builder' => function(EntityRepository $repo){
                    return $repo->createQueryBuilder('c')->addOrderBy('c.name', 'ASC');
                }
            ])
            //->add('isPublished')
            //->add('dateCreated')
            ->add('button', SubmitType::class, ['label' => 'OK!'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Idea::class,
        ]);
    }
}
