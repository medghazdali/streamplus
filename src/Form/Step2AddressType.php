<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Step2AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('addressLine1', TextType::class, [
                'label' => 'form.address.address_line1',
                'attr' => ['class' => 'form-control']
            ])
            ->add('addressLine2', TextType::class, [
                'label' => 'form.address.address_line2',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('city', TextType::class, [
                'label' => 'form.address.city',
                'attr' => ['class' => 'form-control']
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'form.address.postal_code',
                'attr' => ['class' => 'form-control']
            ])
            ->add('stateOrProvince', TextType::class, [
                'label' => 'form.address.state_or_province',
                'attr' => ['class' => 'form-control']
            ])
            ->add('country', CountryType::class, [
                'label' => 'form.address.country',
                'placeholder' => 'Choose your country',
                'attr' => ['class' => 'form-select']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
            'validation_groups' => ['step2'],
        ]);
    }
}
