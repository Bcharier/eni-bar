<?php

namespace App\Controller\Admin;

use App\Entity\Participant;
use App\Entity\Site;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ParticipantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Participant::class;
    }


    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('site');
        /*
        return [
            TextField::new('nom'),
            TextField::new('prenom'),
            TextField::new('pseudo'),
            TextField::new('telephone'),
            TextField::new('mail'),
            AssociationField::new('site')->setFormType(EntityType::class)->setFormTypeOptions(['class' => Site::class, 'choice_label' => 'nom']),
            ChoiceField::new('roles')->setChoices(['Utilisateur' => 'ROLE_USER', 'Administrateur' => 'ROLE_ADMIN'])->allowMultipleChoices(),
            BooleanField::new('actif')
        ];
    }
}
