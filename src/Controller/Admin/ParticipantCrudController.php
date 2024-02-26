<?php

namespace App\Controller\Admin;

use App\Entity\Participant;
use App\Entity\Site;
use Symfony\Component\Form\FormTypeInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class ParticipantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Participant::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('site');
        /*
        return [
            AssociationField::new('participant')->setFormType(Participant::class)->setFormTypeOptions(['class' => Site::class, 'choice_label' => 'nom'])

        ];
    }
    */
}
