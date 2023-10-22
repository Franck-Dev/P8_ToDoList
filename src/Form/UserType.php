<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('password',PasswordType::class)
            ->add('email')
            ->add('roles',ChoiceType::class, [
                'choices'  => [
                    'Admin' => 'ROLE_ADMIN',
                    'Autre' => 'ROLE_USER',],
                'expanded' => true,
                'multiple' => false,
                'required' => true
            ])
        ;
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesAsArray): string {
                    // transform the array to a string
                    return implode(', ', $rolesAsArray);
                },
                function ($rolesAsString): array {
                    // transform the string back to an array
                    return explode(', ', $rolesAsString);
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
