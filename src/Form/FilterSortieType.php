<?php

namespace App\Form;

use App\Entity\Site;
use App\Repository\SiteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sites', EntityType::class, [
                'class' => Site::class,
                'label' => 'Site',
                'choice_label' => 'nom',
                'query_builder' => function(SiteRepository $siteRepository) {
                    return $siteRepository->createQueryBuilder('s')
                                        ->orderBy('s.nom', 'ASC');
                },
            ])
            ->add('nameSearch', SearchType::class, [
                'label' => 'Rechercher : ',
                'required' => false,
            ])
            ->add('dateStart', DateType::class, [
                'label' => 'Entre ',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('dateEnd', DateType::class, [
                'label' => ' et ',
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('checkboxOrganizer', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false,
            ])
            ->add('checkboxRegistered', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit/e',
                'required' => false,
            ])
            ->add('checkboxNotRegistered', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false,
            ])
            ->add('checkboxPast', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false,
            ])
            ->add('filter', SubmitType::class, [
                'label' => 'Rechercher222',
                'attr' => [
                    'action' => 'google.fr',
                    'class' => 'button button-large',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
