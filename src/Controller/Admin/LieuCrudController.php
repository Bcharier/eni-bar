<?php

namespace App\Controller\Admin;

use App\Entity\Lieu;
use App\Entity\Ville;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class LieuCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Lieu::class;
    }

    public function configureFields(string $pageName): iterable
    {
        //yield AssociationField::new('lieu');
        //yield AssociationField::new('lieu')->setFormType(EntityType::class)->setFormTypeOptions(['class' => Lieu::class, 'choice_label' => 'nom']);
        return [
            //IdField::new('id'),
            TextField::new('nom'),
            TextField::new('rue'),
            NumberField::new('latitude'),
            NumberField::new('longitude'),
            AssociationField::new('ville')->setFormType(EntityType::class)->setFormTypeOptions(['class' => Ville::class, 'choice_label' => 'nom']),
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
