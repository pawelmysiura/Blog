<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 2017-11-24
 * Time: 13:28
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormConfigBuilderInterface;
class CreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'create_category_tag.name',
                'translation_domain' => 'form',
                'required' => true
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'create_category_tag.button',
        'translation_domain' => 'form'
            ));
    }

}