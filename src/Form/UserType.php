<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('nom', TextType::class,['label' => 'Last name'])
            ->add('prenom', TextType::class,['label' => 'First name'])
            ->add('date_de_naissance',DateType::class,[
                'label' => 'Birthday',
                'years' => range(1920,2020),
                'widget' => 'single_text',
                'html5' => false,
            ])
            ->add('adresse', TextType::class,['label' => 'Address'])
            ->add('code_postal', TextType::class,['label' => 'Postal code'])
            ->add('ville', TextType::class,['label' => 'City'])
            ->add('pays', TextType::class,['label' => 'Country'])
            ->add('pseudo', TextType::class,['label' => 'Username'])
            ->add('mdp',PasswordType::class,['label' => 'Password'])
            ->add('image',FileType::class,['label' => 'Your picture'])
            ->add('save', SubmitType::class, ['label' => 'Create user'])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
//            'validation_groups' => ['Default','login'],
        ]);
    }
}
