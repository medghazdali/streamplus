<?php

namespace App\Form;

use App\Entity\Payment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\NotBlank;

class Step3PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('creditCardNumber', TextType::class, [
                'label' => 'form.payment.credit_card',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '1234 5678 9012 3456'
                ],
                'required' => true
            ])
            ->add('expirationDate', TextType::class, [
                'mapped' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'MM/YY',
                    'class' => 'form-control',
                    'maxlength' => '5'
                ],
                'help' => 'Format: MM/YY (e.g., 12/25)',
                'constraints' => [
                    new NotBlank(['message' => 'Please enter the expiration date']),
                    new Regex([
                        'pattern' => '/^(0[1-9]|1[0-2])\/\d{2}$/',
                        'message' => 'Expiration date must be in MM/YY format'
                    ])
                ]
            ])
            ->add('cvv', PasswordType::class, [
                'label' => 'form.payment.cvv',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => '123'
                ],
                'required' => true
            ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $payment = $event->getForm()->getData();
            
            if (isset($data['creditCardNumber'])) {
                $data['creditCardNumber'] = preg_replace('/\s+/', '', $data['creditCardNumber']);
            }
            
            if (isset($data['expirationDate']) && is_string($data['expirationDate'])) {
                $date = \DateTime::createFromFormat('m/y', $data['expirationDate']);
                if ($date) {
                    $date->modify('last day of this month');
                    $payment->setExpirationDate($date);
                }
            }
            
            $event->setData($data);
        });

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $payment = $event->getData();
            if ($payment && $payment->getExpirationDate()) {
                $form = $event->getForm();
                $form->get('expirationDate')->setData($payment->getExpirationDate()->format('m/y'));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
            'validation_groups' => ['step3'],
        ]);
    }
}
