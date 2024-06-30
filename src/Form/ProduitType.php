<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Categories;
use App\Entity\Utilisateur;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Vich\UploaderBundle\Form\Type\VichImageType;


class ProduitType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('prix', NumberType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('quantite', NumberType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('info_produit', TextareaType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('statut_produit', CheckboxType::class, [
                'label' => 'Actif',
                'required' => false
            ])
            ->add('ref_prod', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true, // permet de supprimer le fichier, pas seulement la référence de l'entité
                'delete_label' => 'Supprimer l\'image',
                'download_uri' => false, // Désactiver le lien de téléchargement
                'image_uri' => true, // Permettre de voir l'image dans le formulaire
                'asset_helper' => true,
            ])
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'nomCategorie',
                'multiple' => true,
                'expanded' => true  // Utilisez expanded pour transformer en cases à cocher
            ]);
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
