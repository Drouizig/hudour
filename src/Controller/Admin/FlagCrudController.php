<?php

namespace App\Controller\Admin;

use App\Entity\Enum\FlagType;
use App\Entity\Flag;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

class FlagCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Flag::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name'),
            TextField::new('description'),
            ChoiceField::new('type')
                ->setFormType(EnumType::class)
                ->setFormTypeOption('class', FlagType::class),
            CollectionField::new('affixes')
                ->renderExpanded()
                ->useEntryCrudForm(AffixCrudController::class),
        ];
    }

}
