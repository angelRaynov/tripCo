<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start_destination',TextType::class)
            ->add('end_destination',TextType::class)
            ->add('date',TextType::class)
            ->add('hour',TextType::class)
            ->add('price',TextType::class)
            ->add('seats',TextType::class)
            ->add('message',TextType::class)
            ->add('luggage',TextType::class)
            ->add('car',TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
       $resolver->setDefaults(array(
           'data_class' => 'AppBundle\Entity\Offer',
       ));
    }


}
