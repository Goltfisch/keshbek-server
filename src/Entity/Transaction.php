<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *
     * @var \App\Entity\User
     *
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="creditorId", referencedColumnName="id")
     */
    protected $creditor;

    /**
     *
     * @var \App\Entity\User
     *
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="debitorId", referencedColumnName="id")
     */
    protected $debitor;

    /**
     * @ORM\Column(type="integer", name="creditorId", nullable=false)
     */
    private $creditorId;

    /**
     * @ORM\Column(type="integer", name="debitorId", nullable=false)
     */
    private $debitorId;

    /**
     * @ORM\Column(type="float", name="amount", nullable=false)
     */
    private $amount;

    /**
     * @ORM\Column(type="string", length=255, name="reason", nullable=false)
     */
    private $reason;

    /**
     * @ORM\Column(type="datetime", name="transactionDate", nullable=false)
     */
    private $transactionDate;

    /**
     * @ORM\Column(type="datetime", name="createdAt", nullable=false)
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CashUp", inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cashUp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreditorId(): ?int
    {
        return $this->creditorId;
    }

    public function setCreditorId(int $creditorId): self
    {
        $this->creditorId = $creditorId;

        return $this;
    }

    public function getCreditor()
    {
        return $this->creditor;
    }

    public function setCreditor($creditor)
    {
        $this->creditor = $creditor;

        return $this;
    }

    public function getDebitor()
    {
        return $this->debitor;
    }

    public function setDebitor($debitor)
    {
        $this->debitor = $debitor;

        return $this;
    }

    public function getDebitorId(): ?int
    {
        return $this->debitorId;
    }

    public function setDebitorId(int $debitorId): self
    {
        $this->debitorId = $debitorId;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getTransactionDate(): ?\DateTimeInterface
    {
        return $this->transactionDate;
    }

    public function setTransactionDate(\DateTimeInterface $transactionDate): self
    {
        $this->transactionDate = $transactionDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(): self
    {
        $createdAt = new \DateTime();
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCashUp(): ?CashUp
    {
        return $this->cashUp;
    }

    public function setCashUp(?CashUp $cashUp): self
    {
        $this->cashUp = $cashUp;

        return $this;
    }
}
