<?php

namespace App\Entity;

use App\Enum\Subscription;
use App\Repository\UserRegistrationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRegistrationRepository::class)]
#[UniqueEntity(
    fields: ['email'],
    message: 'This email is already in use.',
    groups: ['step1']
)]
class UserRegistration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['exclude'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: ["step1"])]
    #[Groups(['safe'])]
    private ?string $name = null;

    #[ORM\Column(name: 'email', type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank(groups: ["step1"])]
    #[Assert\Email(groups: ["step1"])]
    #[Groups(['safe'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: ["step1"])]
    #[Groups(['safe'])]
    private ?string $subscription = null;

    #[ORM\Column(length: 15)]
    #[Assert\NotBlank(groups: ["step1"])]
    #[Assert\Regex(
      pattern: "/^\+?\d{10,15}$/",
      message: "The phone number must be between 10 and 15 digits and may start with a '+'.",
      groups: ["step1"]
    )]
    #[Groups(['safe'])]
    private ?string $phone = null;
    
    /**
     * @var Collection<int, Address>
     */
    #[ORM\OneToMany(targetEntity: Address::class, mappedBy: 'registeredUser', orphanRemoval: true, cascade: ['persist'])]
    #[Groups(['exclude'])]
    private Collection $addresses;

    /**
     * @var Collection<int, PaymentDetails>
     */
    #[ORM\OneToMany(targetEntity: PaymentDetails::class, mappedBy: 'registeredUser', orphanRemoval: true, cascade: ['persist'])]
    #[Groups(['exclude'])]
    private Collection $paymentDetails;

    public function __construct()
    {
        $this->addresses = new ArrayCollection();
        $this->paymentDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getSubscription(): ?string
    {
        return $this->subscription;
    }

    public function setSubscription(string $subscription): static
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * @return Collection<int, Address>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): static
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
            $address->setRegisteredUser($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): static
    {
        if ($this->addresses->removeElement($address)) {
            // set the owning side to null (unless already changed)
            if ($address->getRegisteredUser() === $this) {
                $address->setRegisteredUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PaymentDetails>
     */
    public function getPaymentDetails(): Collection
    {
        return $this->paymentDetails;
    }

    public function addPaymentDetail(PaymentDetails $paymentDetail): static
    {
        if (!$this->paymentDetails->contains($paymentDetail)) {
            $this->paymentDetails->add($paymentDetail);
            $paymentDetail->setRegisteredUser($this);
        }

        return $this;
    }

    public function removePaymentDetail(PaymentDetails $paymentDetail): static
    {
        if ($this->paymentDetails->removeElement($paymentDetail)) {
            // set the owning side to null (unless already changed)
            if ($paymentDetail->getRegisteredUser() === $this) {
                $paymentDetail->setRegisteredUser(null);
            }
        }

        return $this;
    }
}
