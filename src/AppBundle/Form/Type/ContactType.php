<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array(
                'label' => 'contact.email',
                'translation_domain' => 'form',
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Email()
                )

            ))
            ->add('subject', TextType::class, array(
                'label' => 'contact.subject',
                'translation_domain' => 'form',
                'constraints' => array(
                    new Assert\NotBlank()
                )
            ))
            ->add('message', TextareaType::class, array(
                'label' => 'contact.message',
                'translation_domain' => 'form',
                'constraints' => array(
                    new Assert\NotBlank()
                )
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'contact.button',
                'translation_domain' => 'form'
            ));
    }


}