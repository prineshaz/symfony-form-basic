<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\PaymentDetails;
use App\Entity\UserRegistration;
use App\Event\RegistrationCompletedEvent;
use App\Form\AddressType;
use App\Form\PaymentType;
use App\Form\UserType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class RegisterController extends AbstractController
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/register/start', name: 'register_start')]
    public function start(SessionInterface $session): Response
    {
        $userData = $session->get('user_data', new UserRegistration());
        $form     = $this->createForm(UserType::class, $userData, [
            'validation_groups' => ['step1'],
        ]);

        return $this->render('register/start.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/register/step1', name: 'register_step1')]
    public function step1(Request $request, SessionInterface $session): Response
    {
        $userData = $session->get('user_data', new UserRegistration());

        $form = $this->createForm(UserType::class, $userData, [
            'validation_groups' => ['step1'],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('user_data', $userData);
            $addressData = $session->get('address_data', new Address());

            // Render form for step 2 (AddressType).
            return $this->render(
                'register/_step2.html.twig',
                [
                    'form' => $this->createForm(AddressType::class, $addressData, [
                        'validation_groups' => ['address'],
                    ])->createView(),
                ]
            );
        }

        return $this->render('register/_step1.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/register/step2', name: 'register_step2')]
    public function step2(Request $request, SessionInterface $session, SerializerInterface $serializer): Response
    {
        // Retrieve the Address entity from session if it exists, or create a new one
        $addressData = $session->get('address_data', new Address());

        $form = $this->createForm(AddressType::class, $addressData, [
            'validation_groups' => ['address'],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Store address in session.
            $session->set('address_data', $addressData);
            // Get user data so far from session.
            $userData = $session->get('user_data', new UserRegistration());

            // Check if the subscription is "premium"
            if ($userData->getSubscription() !== 'premium') {
                $emptyForm = $this->createEmptyForm();

                return $this->render('register/_confirm.html.twig', [
                    'userData'    => $serializer->normalize($userData, null, ['groups' => ['safe']]),
                    'addressData' => $serializer->normalize($addressData, null, ['groups' => ['safe']]),
                    'form'        => $emptyForm->createView(),
                ]);
            }

            $paymentData = $session->get('payment_data', new PaymentDetails());

            return $this->render('register/_step3.html.twig', [
                'form' => $this->createForm(PaymentType::class, $paymentData, [
                    'validation_groups' => ['step3'],
                ])->createView(),
            ]);
        }

        // If the form isn't submitted or valid, return the form view as HTML.
        return $this->render('register/_step2.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/register/step3', name: 'register_step3')]
    public function step3(Request $request, SessionInterface $session, SerializerInterface $serializer): Response
    {
        $userData    = $session->get('user_data', new UserRegistration());
        $addressData = $session->get('address_data', new UserRegistration());
        // Check if the subscription is "premium"
        if ($userData->getSubscription() !== 'premium') {
            $emptyForm = $this->createEmptyForm();

            return $this->render('register/_confirm.html.twig', [
                'userData'    => $serializer->normalize($userData, null, ['groups' => ['safe']]),
                'addressData' => $serializer->normalize($addressData, null, ['groups' => ['safe']]),
                'form'        => $emptyForm->createView(),
            ]);
        }

        $paymentData = $session->get('payment_data', new PaymentDetails());

        $form = $this->createForm(PaymentType::class, $paymentData, [
            'validation_groups' => ['step3'],
        ]);

        $form->handleRequest($request);

        // If Form submitted and valid.
        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('payment_data', $paymentData);
            $emptyForm = $this->createEmptyForm();

            return $this->render('register/_confirm.html.twig', [
                'userData'    => $serializer->normalize($userData, null, ['groups' => ['safe']]),
                'addressData' => $serializer->normalize($addressData, null, ['groups' => ['safe']]),
                'paymentData' => $serializer->normalize($paymentData, null, ['groups' => ['safe']]),
                'form'        => $emptyForm->createView(),
            ]);
        }

        // If the form isn't submitted or valid, return the form view as HTML.
        return $this->render('register/_step3.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/register/confirm', name: 'register_confirm')]
    public function confirm(Request $request, SessionInterface $session, EventDispatcherInterface $dispatcher, SerializerInterface $serializer): Response
    {
        $userData    = $session->get('user_data', new UserRegistration());
        $addressData = $session->get('address_data', new Address());
        $paymentData = $session->get('payment_data', new PaymentDetails());
        // As this is a presentation phase, and we want to reuse js, prepare an empty form.
        $emptyForm = $this->createEmptyForm();
        $emptyForm->handleRequest($request);

        // If Form submitted (means user has confirmed).
        if ($emptyForm->isSubmitted()) {
            // Dispatch completed event
            try {
                $event = new RegistrationCompletedEvent($userData, $addressData, $paymentData);
                $dispatcher->dispatch($event, 'registration.completed');
                $this->addFlash('success', 'Registration completed successfully!');
                // Clear session data here. If error, we don't want user to fill in details again.
                $session->remove('user_data');
                $session->remove('address_data');
                $session->remove('payment_data');
            } catch (\Exception $e) {
                // Handle the error: log it, add a flash message, or re-render with an error message.
                $this->logger->error('Error during registration confirmation. {error}', [
                    'error' => $e->getMessage(),
                ]);
                $this->addFlash('error', 'An error occurred while saving your registration. Please try again.');
            }

            return $this->render('register/_complete.html.twig', [
                'userData' => $serializer->normalize($userData, null, ['groups' => ['safe']]),
            ]);
        }

        // If the form isn't submitted or valid, return the form view as HTML.
        return $this->render('register/_confirm.html.twig', [
            'userData'    => $serializer->normalize($userData, null, ['groups' => ['safe']]),
            'addressData' => $serializer->normalize($addressData, null, ['groups' => ['safe']]),
            'paymentData' => $serializer->normalize($paymentData, null, ['groups' => ['safe']]),
            'form'        => $emptyForm->createView(),
        ]);
    }

    #[Route('/register/complete', name: 'register_complete')]
    public function complete(Request $request, SessionInterface $session, SerializerInterface $serializer): Response
    {
        $userData = $session->get('user_data', new UserRegistration());

        return $this->render('register/_complete.html.twig', [
            'userData' => $serializer->normalize($userData, null, ['groups' => ['safe']]),
        ]);
    }

    private function createEmptyForm(): \Symfony\Component\Form\FormInterface
    {
        return $this->createFormBuilder()
          ->setMethod('POST')
          ->getForm();
    }
}
