<?php

namespace App\Controller\Admin;

use App\Entity\Affix;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AffixCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Affix::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('stripping'),
            TextField::new('prefix'),
            TextField::new('condition'),
            TextField::new('morphologicalFields'),
        ];
    }

}
