<?php
// src/Event/RegistrationCompletedEvent.php
namespace App\Event;

use App\Entity\UserRegistration;
use App\Entity\Address;
use App\Entity\PaymentDetails;

use Symfony\Contracts\EventDispatcher\Event;

class RegistrationCompletedEvent extends Event
{
    public const NAME = 'registration.completed';

    private UserRegistration $userData;
    private ?Address $address;
    private ?PaymentDetails $paymentDetails;

    public function __construct(UserRegistration $userData, ?Address $address = null, ?PaymentDetails $paymentDetails = null)
    {
        $this->userData = $userData;
        $this->address = $address;
        $this->paymentDetails = $paymentDetails;
    }

    public function getUserFormData(): UserRegistration
    {
        return $this->userData;
    }

    public function getAddressData(): ?Address
    {
        return $this->address;
    }

    public function getPaymentData(): ?PaymentDetails
    {
        return $this->paymentDetails;
    }
}