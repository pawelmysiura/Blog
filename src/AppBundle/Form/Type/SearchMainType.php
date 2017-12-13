<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 2017-11-24
 * Time: 13:28
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
class SearchMainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($options['action'])
            ->add('search', SearchType::class, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Search'
                )
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Search',
            ));
    }

}