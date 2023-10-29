<?php

namespace App\Controller\Admin;

use App\Entity\Word;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class WordCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Word::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('word'),
            TextField::new('morphologicalFields'),
            TextField::new('comment'),
            AssociationField::new('flags')->autocomplete(),
        ];
    }

}
