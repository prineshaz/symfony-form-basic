<?php

namespace App\Entity;

use App\Repository\PaymentDetailsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PaymentDetailsRepository::class)]
class PaymentDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['exclude'])]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(groups: ["step3"])]
    #[Assert\Regex(
      pattern: "/^\d{13,19}$/",
      message: "The credit card number must be between 13 and 19 digits.",
      groups: ["step3"]
    )]
    #[Groups(['exclude'])]
    private ?string $cardNumber = null;

    #[ORM\Column(length: 3)]
    #[Assert\NotBlank(groups: ["step3"])]
    #[Assert\Regex(
      pattern: "/^\d{3}$/",
      message: "The CVV must be exactly 3 digits.",
      groups: ["step3"]
    )]
    #[Groups(['exclude'])]
    private ?string $cvv = null;

    #[ORM\Column(length: 7)]
    #[Assert\NotBlank(groups: ["step3"])]
    #[Groups(['safe'])]
    private ?string $expirationDate = null;

    #[ORM\ManyToOne(inversedBy: 'paymentDetails')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['exclude'])]
    private ?UserRegistration $registeredUser = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCardNumber(): ?string
    {
        return $this->cardNumber;
    }

    public function setCardNumber(string $cardNumber): static
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    public function getCvv(): ?string
    {
        return $this->cvv;
    }

    public function setCvv(string $cvv): static
    {
        $this->cvv = $cvv;

        return $this;
    }

    public function getExpirationDate(): ?string
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(string $expirationDate): static
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    public function getRegisteredUser(): ?UserRegistration
    {
        return $this->registeredUser;
    }

    public function setRegisteredUser(?UserRegistration $registeredUser): static
    {
        $this->registeredUser = $registeredUser;

        return $this;
    }
}
