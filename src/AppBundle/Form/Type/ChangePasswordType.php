<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 2017-11-16
 * Time: 16:50
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;
class ChangePasswordType extends AbstractType
{
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('currPassword', PasswordType::class, array(
                    'label' => 'change_password.curr_password' ,
                    'mapped' => false,
                    'translation_domain' => 'form',
                    'constraints' => array(
                        new UserPassword(array(
                            'message' => 'Incorrect password. Please try again'
                        ))
                    )
                ))
                ->add('plainPassword', RepeatedType::class, array(
                    'first_options' => array(
                        'label' => 'change_password.new_password',
                        'translation_domain' => 'form',
                    ),
                    'second_options' => array(
                        'label' => 'change_password.repeat_password',
                        'translation_domain' => 'form',
                    ),
                    'constraints' => array(
                        new Assert\NotBlank()
                    )
                ))
                ->add('submit', SubmitType::class, array(
                    'label' => 'change_password.button',
                    'translation_domain' => 'form'
                ));
        }

        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults(array(
                'data_class' => 'AppBundle\Entity\User'
            ));
        }
}