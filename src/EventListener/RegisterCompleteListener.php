<?php

namespace App\EventListener;

use App\Entity\Address;
use App\Entity\PaymentDetails;
use App\Entity\UserRegistration;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class RegisterCompleteListener
{
    private LoggerInterface $logger;
    private EntityManagerInterface $entityManager;

    public function __construct(LoggerInterface $logger, EntityManagerInterface $entityManager)
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    #[AsEventListener(event: 'registration.completed')]
    public function onRegistrationCompleted($event): void
    {
        // Saving data in the database executed here, away from contollers.
        // Controller owns the flow, but not the data.
        // This is a good practice to separate concerns.

        /** @var UserRegistration $userData */
        $userData = $event->getUserFormData();
        /** @var Address $addressData */
        $addressData = $event->getAddressData();
        /** @var PaymentDetails $paymentData */
        $paymentData = $event->getPaymentData();

        if ($addressData) {
            $userData->addAddress($addressData);
            $addressData->setRegisteredUser($userData);
            $this->entityManager->persist($addressData);
        }

        if ($paymentData && 'premium' == $userData->getSubscription()) {
            $userData->addPaymentDetail($paymentData);
            $paymentData->setRegisteredUser($userData);
            $this->entityManager->persist($paymentData);
        }

        try {
            // Persist data in entity.
            $this->entityManager->persist($userData);
            $this->entityManager->flush();
            // Log success
            $this->logger->info('Registration completed for user: {name}', [
                'name' => $userData->getName(),
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Error saving registration data: {error}', [
                'error' => $e->getMessage(),
            ]);
            throw new \Exception('Could not save registration data: '.$e->getMessage());
        }
    }
}
