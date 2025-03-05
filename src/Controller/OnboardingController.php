<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Address;
use App\Entity\Payment;
use App\Form\Step1UserType;
use App\Form\Step2AddressType;
use App\Form\Step3PaymentType;
use App\Form\Step4ConfirmationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;

class OnboardingController extends AbstractController
{
    #[Route('/', name: 'app_root')]
    public function root(): Response
    {
        return $this->redirectToRoute('onboarding_step1');
    }

    #[Route('/onboarding/step1', name: 'onboarding_step1')]
    public function step1(Request $request): Response
    {
        $user = $request->getSession()->get('user', new User());

        $form = $this->createForm(Step1UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $request->getSession()->set('user', $user);
            return $this->redirectToRoute('onboarding_step2');
        }

        return $this->render('onboarding/step1.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/onboarding/step2', name: 'onboarding_step2')]
    public function step2(Request $request, EntityManagerInterface $em): Response
    {
        $user = $request->getSession()->get('user');
        if (!$user) {
            return $this->redirectToRoute('onboarding_step1');
        }

        // If coming back from step3, get the existing address
        $address = $user->getAddresses()->first() ?: new Address();
        $address->setUser($user);
        
        $form = $this->createForm(Step2AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$user->getAddresses()->contains($address)) {
                $user->addAddress($address);
            }
            $request->getSession()->set('user', $user);

            if ($user->getSubscriptionType() === 'premium') {
                return $this->redirectToRoute('onboarding_step3');
            } else {
                return $this->redirectToRoute('onboarding_step4');
            }
        }

        return $this->render('onboarding/step2.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/onboarding/step3', name: 'onboarding_step3')]
    public function step3(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $request->getSession()->get('user');
        if (!$user) {
            return $this->redirectToRoute('onboarding_step1');
        }

        // Get or create payment
        $payment = $user->getPayments()->first() ?: new Payment();
        $payment->setUser($user);

        $form = $this->createForm(Step3PaymentType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$user->getPayments()->contains($payment)) {
                $user->addPayment($payment);
            }
            $request->getSession()->set('user', $user);
            return $this->redirectToRoute('onboarding_step4');
        }

        return $this->render('onboarding/step3.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    #[Route('/onboarding/step4', name: 'onboarding_step4')]
    public function step4(Request $request, EntityManagerInterface $em): Response
    {
        $user = $request->getSession()->get('user');
        if (!$user) {
            return $this->redirectToRoute('onboarding_step1');
        }

        // Format payment data for display
        $formattedPayments = [];
        foreach ($user->getPayments() as $payment) {
            $expirationDate = $payment->getExpirationDate();
            $formattedPayments[] = [
                'creditCardNumber' => $payment->getCreditCardNumber(),
                'expirationDate' => $expirationDate instanceof \DateTimeInterface ? $expirationDate->format('m/Y') : $expirationDate
            ];
        }

        $form = $this->createForm(Step4ConfirmationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                VarDumper::dump($form->getErrors(true));
            } else {
                $em->persist($user);
                $em->flush();
                $request->getSession()->remove('user');
                return $this->redirectToRoute('onboarding_success');
            }
        }

        return $this->render('onboarding/step4.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'formattedPayments' => $formattedPayments
        ]);
    }

    #[Route('/onboarding/success', name: 'onboarding_success')]
    public function success(): Response
    {
        return $this->render('onboarding/success.html.twig');
    }
}
