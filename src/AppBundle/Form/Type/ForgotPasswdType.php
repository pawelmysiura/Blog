<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 2017-11-12
 * Time: 11:02
 */

namespace AppBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Asssert;
class ForgotPasswdType extends AbstractType
{
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('plainPassword', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'first_options' => array(
                        'label' => 'password'
                    ),
                    'second_options' => array(
                        'label' => 'forgot_password.c_password',
                    ),
                    'translation_domain' => 'form',
                    'constraints' => array(
                        new Asssert\NotBlank(),
                        new Asssert\Length(array(
                            'min' => 8
                        ))
                    )
                ))
                ->add('submit', SubmitType::class, array(
                    'label' => 'Submit'
                ));

        }
}