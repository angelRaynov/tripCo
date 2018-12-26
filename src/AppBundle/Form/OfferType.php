<?php

namespace AppBundle\Form;

use AppBundle\Entity\Car;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('start_destination', TextType::class)
            ->add('end_destination', TextType::class)
            ->add('date', DateType::class)
            ->add('hour', TextType::class)
            ->add('price', TextType::class)
            ->add('seats', TextType::class)
            ->add('message', TextType::class)
            ->add('luggage', ChoiceType::class, array(
                'choices' => array(
                    '' => '',
                    'small' => 'small',
                    'medium' => 'medium',
                    'large' => 'large'
                )
            ))
            ->add('car', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Offer',
        ));
    }


}
