<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
class ChangeEmailType extends AbstractType
{
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('password', PasswordType::class, array(
                    'label' => 'password',
                    'mapped' => false,
                    'translation_domain' => 'form',
                    'constraints' => array(
                        new UserPassword(array(
                            'message' => 'Incorrect password. Please try again'
                        ))
                    )
                ))
                ->add('newEmail', EmailType::class, array(
                    'label' => 'change_email.new_email',
                    'translation_domain' => 'form'

                ))
                ->add('submit', SubmitType::class, array(
                    'label' => 'change_email.button',
                    'translation_domain' => 'form'
                ));
        }


}