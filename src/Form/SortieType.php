<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Lieu;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut')
            ->add('duree')
            ->add('dateLimiteInscription')
            ->add('nbInscriptionsMax')
            ->add('infosSortie')
            /*
            ->add('etat', EntityType::class, [
                'class' => Etat::class,
                'choice_label' => 'libelle',
                'mapped' => false,
                ])
            */
            ->add('organisateur', EntityType::class, [
                //'label' => "Hello",
                'class' => Participant::class,
                'choice_label' => 'pseudo',
            ])
            ->add('site', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'nom',
            ])
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'nom',
            ])
            ->add('submit', SubmitType::class, array('label'  => 'Enregistrer la sortie'))
            ->add('publish', SubmitType::class, array('label'  => 'Publier la sortie'))
            ->add('cancel', ResetType::class, array('label'  => 'Annuler'))
            //->add('save', SubmitType::class, ['label' => 'Enregistré la sortie', 'class' => 'submit-button'])
            //->add('publish', SubmitType::class, ['label' => 'Publié la sortie', 'class' => 'submit-button'])
            //->getForm();
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
