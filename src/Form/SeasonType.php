<?php

namespace App\Form;

use App\Entity\Season;
use App\Entity\Serie;
use App\Repository\SerieRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number')
            ->add('firstAirDate', DateType::class, [
                //pour avoir un mode calendrier -> single_text
                'widget' => 'single_text',
                'html5' => true
            ])
            ->add('overview')
            ->add('poster')
            ->add('tmdbId')
            //EntityType-> Type entité -> info en BDD
            ->add('serie', EntityType::class, [
                //quelle entité est liée
                'class'=>Serie::class,
                //quel attribut servira à afficher l'information
                'choice_label'=>'name',
                'query_builder' => function(SerieRepository $serieRepository){
                    $qb = $serieRepository->createQueryBuilder('s');
                    $qb->addOrderBy('s.name','ASC');
                    return $qb;
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
