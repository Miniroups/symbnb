<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends AbstractType
{
    /**
     * Récupère la configuration du titre & du placeholder
     * 
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    public function getConfig($label, $placeholder, $options = [])
    {
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class, 
                $this->getConfig("Titre", "Ajouter un titre")
            )
            ->add(
                'slug',
                TextType::class,
                $this->getConfig("Adresse web", "Tapez l'adresse web (automatique)", [
                    'required' => false
                ])
            )
            ->add(
                'coverImage',
                UrlType::class,
                $this->getConfig("URL de l'image de couverture", "Donnez l'adrese de votre image")
            )
            ->add(
                'introduction',
                TextType::class,
                $this->getConfig("Introduction", "Donnez une description globale de l'annonce")
            )
            ->add(
                'content', 
                TextareaType::class, 
                $this->getConfig("Contenu", "Donnez une description détaillée !")
            )
            ->add(
                'rooms', 
                IntegerType::class, 
                $this->getConfig("Nombre de chambres", "Nombre de chambres dans la location")
            )
            ->add(
                'price', 
                MoneyType::class, 
                $this->getConfig("Prix par nuit", "Prix de la location pour une nuit")
            )
            ->add(
                'images',
                CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'allow_add' => true,
                    'allow_delete' => true
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}