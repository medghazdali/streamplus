<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use App\Validator\FutureDate;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
#[ORM\Table(name: 'payments')]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: 'string', length: 16, nullable: true)]
    #[Assert\NotBlank(groups: ['step3'])]
    #[Assert\Regex(pattern: '/^\d{16}$/', groups: ['step3'], message: 'Credit card must be 16 digits')]
    private ?string $creditCardNumber = null;

    #[ORM\Column(type: 'date', nullable: true)]
    #[Assert\NotBlank(groups: ['step3'])]
    #[FutureDate(groups: ['step3'])]
    private ?\DateTimeInterface $expirationDate = null;

    #[ORM\Column(length: 3)]
    #[Assert\NotBlank(groups: ['step3'])]
    #[Assert\Regex(pattern: '/^\d{3}$/', groups: ['step3'], message: 'CVV must be 3 digits')]
    private ?string $cvv = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getCreditCardNumber(): ?string
    {
        return $this->creditCardNumber;
    }

    public function setCreditCardNumber(?string $creditCardNumber): self
    {
        $this->creditCardNumber = $creditCardNumber;
        return $this;
    }

    public function getExpirationDate(): ?\DateTimeInterface
    {
        return $this->expirationDate;
    }

    public function setExpirationDate($expirationDate): self
    {
        if (is_string($expirationDate)) {
            $date = \DateTime::createFromFormat('m/y', $expirationDate);
            if ($date) {
                $date->modify('last day of this month');
                $this->expirationDate = $date;
            }
        } elseif ($expirationDate instanceof \DateTimeInterface) {
            $this->expirationDate = $expirationDate;
        }
        return $this;
    }

    public function getCvv(): ?string
    {
        return $this->cvv;
    }

    public function setCvv(?string $cvv): self
    {
        $this->cvv = $cvv;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
} 