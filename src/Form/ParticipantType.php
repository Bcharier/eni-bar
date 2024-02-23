<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo')
            ->add('prenom')
            ->add('nom')
            ->add('telephone')
            ->add('mail')
            ->add('password',PasswordType::class)
            ->add('reset', ResetType::class, array(
                'label'  => 'Annuler modifications',
                'attr'   =>  array(
                    'class'   => 'reset-button')))
            ->add('submit', SubmitType::class, array(
                'label'  => 'Valider',
                'attr'   =>  array(
                    'class'   => 'submit-button')))
            ->add('imageFile', VichImageType::class, [
                'label' => 'Photo de profil',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }


}
