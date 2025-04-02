<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['exclude'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: ['address'])]
    #[Groups(['safe'])]
    private ?string $firstLine = null;

    #[ORM\Column(length: 255, nullable: false, options: ['default' => ''])]
    #[Groups(['safe'])]
    private ?string $secondLine = '';

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: ['address'])]
    #[Groups(['safe'])]
    private ?string $postCode = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: ['address'])]
    #[Groups(['safe'])]
    private ?string $city = null;

    #[ORM\ManyToOne(inversedBy: 'addresses')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['exclude'])]
    private ?UserRegistration $registeredUser = null;

    #[ORM\Column(length: 255, nullable: false, options: ['default' => ''])]
    #[Groups(['safe'])]
    private ?string $state_province = '';

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(groups: ['address'])]
    #[Groups(['safe'])]
    private ?string $country = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstLine(): ?string
    {
        return $this->firstLine;
    }

    public function setFirstLine(string $firstLine): static
    {
        $this->firstLine = $firstLine;

        return $this;
    }

    public function getSecondLine(): ?string
    {
        return $this->secondLine;
    }

    public function setSecondLine(string $secondLine): static
    {
        $this->secondLine = $secondLine;

        return $this;
    }

    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    public function setPostCode(string $postCode): static
    {
        $this->postCode = $postCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

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

    public function getStateProvince(): ?string
    {
        return $this->state_province;
    }

    public function setStateProvince(string $state_province): static
    {
        $this->state_province = $state_province;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }
}
