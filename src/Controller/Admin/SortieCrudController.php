<?php

namespace App\Controller\Admin;

use App\Entity\Etat;
use App\Entity\Site;
use App\Entity\Sortie;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
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
            TextEditorField::new('infosSortie')->setLabel('Description de la sortie'),
            AssociationField::new('site')->setFormType(EntityType::class)->setFormTypeOptions(['class' => Site::class, 'choice_label' => 'nom']),
            AssociationField::new('etat')->setFormType(EntityType::class)->setFormTypeOptions(['class' => Etat::class, 'choice_label' => 'libelle']),
        ];
    }
}
