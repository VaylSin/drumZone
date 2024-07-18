<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Controller\Admin\CategoryCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnIndex()->hideOnForm(),
            TextField::new('name'),
            TextField::new('description'),
            NumberField::new('price'),
            TextField::new('sku'),
            NumberField::new('stockQuantity'),
            DateTimeField::new('updatedAt')->setFormat('yyyy-MM-dd HH:mm:ss')->hideOnIndex()->hideOnForm(),
            DateTimeField::new('createdAt')->setFormat('yyyy-MM-dd HH:mm:ss')->hideOnForm(),
            BooleanField::new('isActive')->hideOnForm(),
            BooleanField::new('lightOn')->hideOnForm(),
            NumberField::new('rate'),
            TextField::new('delivery_area'),
            NumberField::new('delivery_delay')
            ->formatValue(function ($value) {
                return $value . ' jours'; // Ajoute ' jours' à la valeur numérique
            }),
            NumberField::new('discount'),
            AssociationField::new('category', 'Category')
                ->setCrudController(CategoryCrudController::class)
                ->formatValue(function ($value, $entity) {
                    // Assurez-vous que getCategory() retourne bien un objet Category
                    $category = $entity->getCategory();
                    if (!$category) {
                        return 'No Category';
                    }
                    // Vérifiez que getName() est bien appelé sur un objet Category et retourne une chaîne
                    return $category->getName();
                }),

        ];
    }
}
