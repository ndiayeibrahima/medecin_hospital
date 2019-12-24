<?php

namespace App\Form;

use App\Entity\Medecin;
use App\Entity\Service;
use App\Entity\Specialite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class MedecinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prenom')
            ->add('nom')
            ->add('tel')
            ->add('dateNais',BirthdayType::class,[
                'widget'=>'single_text'
            ])
            ->add('service',EntityType::class,[
                'class' => Service::class,
                'choice_label'=> 'libelle'
            ])
            //Pour champs d entity il faut donner le type= EntityType , la classe de reference enfin la façon de l afficher
            ->add('specialites',EntityType::class,[
                'class' => Specialite::class,
                'required' => true,
            // Specialite.libelle et si utilise 'choice_label'=> 'id' je vais avoir l id cad Specialite.id
                'choice_label'=> 'libelle',
            //Comme que c est une collection on ajoute 
                'multiple'=> true,
                'expanded'=>true,
            //avec 'by_reference'=> false ca marche bien sans probleme 
                'by_reference'=> false
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Medecin::class,
        ]);
    }
}
