<?php

// src/Form/MessagesType.php

namespace App\Form;

use App\Entity\Messages;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('message')
            ->add('destinataire', EntityType::class, [
                'class' => Utilisateur::class,
                'choice_label' => 'email',  // Utilisez l'attribut approprié de l'entité Utilisateur pour l'affichage
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Messages::class,
        ]);
    }
}
