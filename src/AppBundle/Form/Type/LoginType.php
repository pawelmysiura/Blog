<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 2017-11-10
 * Time: 17:55
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Test\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
class LoginType extends AbstractType
{
        public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('username', TextType::class, array(
                   'label' => 'username',
                    'translation_domain' => 'form'
                ))
                ->add('password', PasswordType::class, array(
                    'label' => 'password',
                    'translation_domain' => 'form'
                ))
                ->add('_remember_me', CheckboxType::class, array(
                    'label' => 'login.remember',
                    'translation_domain' => 'form',
                    'required' => false
                ))
                ->add('submit', SubmitType::class, array(
                    'label' => 'login.button',
                    'translation_domain' => 'form'
                ));
        }
}