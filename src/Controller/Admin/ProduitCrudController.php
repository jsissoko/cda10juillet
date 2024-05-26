<?php
namespace App\Controller\Admin;

use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use Vich\UploaderBundle\Form\Type\VichImageType;
class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),  // Hide on form as it's automatically generated
            TextField::new('nom'),
            MoneyField::new('prix')->setCurrency('EUR'),
            IntegerField::new('quantite'),
            TextareaField::new('info_produit'),
            BooleanField::new('statut_produit')->renderAsSwitch(false),
            TextField::new('ref_prod'),
            ImageField::new('image')->setUploadDir('public/images/products/')
            ->setBasePath('/images/products/')  // Chemin accessible via l'URL
            ->onlyOnIndex()  // Affiche ce champ uniquement sur la page d'index  // le chemin du répertoire où les fichiers sont physiquement stockés

           
        ];
    }
}
