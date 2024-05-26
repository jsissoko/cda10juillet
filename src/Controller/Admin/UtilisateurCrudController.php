<?php

namespace App\Controller\Admin;

use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\PasswordField;

class UtilisateurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Utilisateur::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(), // Masquer dans le formulaire
            TextField::new('prenom'),
            EmailField::new('email'),
            BooleanField ::new('isActive'),
            // PasswordField::new('password'),
            // Autres champs selon votre entité Utilisateur...

            ChoiceField::new('roles')
                ->setChoices([
                    'Utilisateur' => 'ROLE_USER',
                    'Employé' => 'ROLE_EMPLOYE',  // Si applicable
                    'Administrateur' => 'ROLE_ADMIN',
                    'SuperAdministrateur' => 'ROLE_SUPER_ADMIN',
                ])
                ->allowMultipleChoices()
                ->renderExpanded(), // Affiche les choix sous forme de boutons radio/checkbox
        ];
    }
}
