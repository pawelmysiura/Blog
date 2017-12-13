<?php

namespace AppBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'label' => 'create_post.title',
                'translation_domain' => 'form',
                'attr' => array(
                    'placeholder' => 'Title'
                )
            ))
            ->add('category', EntityType::class, array(
                'label' => 'create_post.category',
                'translation_domain' => 'form',
                'class' => 'AppBundle\Entity\Category',
                'choice_label' => 'name',
                'placeholder' => 'Choice category'
            ))
            ->add('tags', EntityType::class, array(
                'label' => 'create_post.tags',
                'translation_domain' => 'form',
                'class' => 'AppBundle\Entity\Tag',
                'multiple' => true,
                'choice_label' => 'name',
                'placeholder' => 'Add tag',
            ))
            ->add('content', TextareaType::class, array(
                'label' => 'create_post.content',
                'translation_domain' => 'form'
            ))
            ->add('publishedDate', DateTimeType::class, array(
                'label' => 'create_post.publish',
                'translation_domain' => 'form',
                'data' => new \DateTime()
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'create_post.button',
                'translation_domain' => 'form'
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Post'
        ));
    }
}