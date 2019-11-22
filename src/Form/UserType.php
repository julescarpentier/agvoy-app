<?php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email')
            ->add('firstname')
            ->add('familyname')
            ->add('roles', ChoiceType::class, [
            'choices' => [
                'Administrateur' => "ROLE_ADMIN",
                'Owner' => "ROLE OWNER",
                'Client' => "ROLE_CLIENT"
            ],
            'multiple' => true,
            'expanded' => true
        ])
            ->add('password')
            ->add('client')
            ->add('owner');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
