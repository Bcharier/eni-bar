<?php

namespace App\Controller\Admin;

use App\Entity\Sortie;
use App\Entity\Lieu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class SortieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Sortie::class;
    }

    public function configureFields(string $pageName): iterable
    {
        //yield AssociationField::new('lieu');
        //yield AssociationField::new('lieu')->setFormType(EntityType::class)->setFormTypeOptions(['class' => Lieu::class, 'choice_label' => 'nom']);
        return [
            //IdField::new('id'),
            TextField::new('nom'),
            DateTimeField::new('dateHeureDebut'),
            NumberField::new('duree'),
            DateTimeField::new('dateLimiteInscription'),
            NumberField::new('nbInscriptionsMax'),
            TextEditorField::new('InfosSortie'),
            AssociationField::new('lieu')->setFormType(EntityType::class)->setFormTypeOptions(['class' => Lieu::class, 'choice_label' => 'nom']),
        ];
    }
    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
