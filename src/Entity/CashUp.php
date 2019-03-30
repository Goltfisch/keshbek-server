<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CashUpRepository")
 */
class CashUp
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creditorId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $debitorId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\State", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $stateId;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="cashUpId", orphanRemoval=true)
     */
    private $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreditorId(): ?User
    {
        return $this->creditorId;
    }

    public function setCreditorId(?User $creditorId): self
    {
        $this->creditorId = $creditorId;

        return $this;
    }

    public function getDebitorId(): ?User
    {
        return $this->debitorId;
    }

    public function setDebitorId(?User $debitorId): self
    {
        $this->debitorId = $debitorId;

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

    public function getStateId(): ?State
    {
        return $this->stateId;
    }

    public function setStateId(State $stateId): self
    {
        $this->stateId = $stateId;

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setCashUpId($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->contains($transaction)) {
            $this->transactions->removeElement($transaction);
            // set the owning side to null (unless already changed)
            if ($transaction->getCashUpId() === $this) {
                $transaction->setCashUpId(null);
            }
        }

        return $this;
    }
}
