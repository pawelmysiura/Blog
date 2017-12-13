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
class RegisterType extends AbstractType
{
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('username', TextType::class, array(
                    'label' => 'username',
                    'translation_domain' => 'form'
                ))
                ->add('plainPassword', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'first_options' => array(
                        'label' => 'password'
                    ),
                    'second_options' => array(
                        'label' => 'register.c_password'
                    ),
                    'translation_domain' => 'form',
                ))
                ->add('email', EmailType::class, array(
                    'label' => 'register.email',
                    'translation_domain' => 'form',
                ))
                ->add('checkbox', CheckboxType::class, array(
                    'label' => 'register.terms',
                    'translation_domain' => 'form',
                    'mapped' => false,
                    'constraints' => new Asssert\IsTrue()
                ))
                ->add('submit', SubmitType::class, array(
                    'label' => 'register.button',
                    'translation_domain' => 'form'
                ));

        }

        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults(array(
                'data_class' => 'AppBundle\Entity\User',
            ));
        }
}