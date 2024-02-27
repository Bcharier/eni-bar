<?php

namespace App\Controller\Admin;

use App\Entity\Etat;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Lieu;
use App\Entity\Participant;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class SortieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sortie::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom'),
            DateTimeField::new('dateHeureDebut'),
            NumberField::new('duree'),
            DateTimeField::new('dateLimiteInscription'),
            NumberField::new('nbInscriptionsMax'),
            TextEditorField::new('infosSortie')->setLabel('Description de la sortie'),
            AssociationField::new('lieu')->setFormType(EntityType::class)->setFormTypeOptions(['class' => Lieu::class, 'choice_label' => 'nom']),
            AssociationField::new('organisateur')->setFormType(EntityType::class)->setFormTypeOptions(['class' => Participant::class, 'choice_label' => 'pseudo']),
            AssociationField::new('site')->setFormType(EntityType::class)->setFormTypeOptions(['class' => Site::class, 'choice_label' => 'nom']),
            AssociationField::new('etat')->setFormType(EntityType::class)->setFormTypeOptions(['class' => Etat::class, 'choice_label' => 'libelle']),
        ];
    }
}
