<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrderHeaderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=OrderHeaderRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
#[ApiResource(
    collectionOperations: ['get'],
    itemOperations: ['get'],
)]
class OrderHeader
{
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $now = new \DateTime('now', new \DateTimeZone('Europe/Istanbul'));
        $this->setUpdatedAt($now);
        if (!$this->createdAt) {
            $this->setCreatedAt($now);
        }
    }


    /**
     * @ORM\Id
     * @Groups({"order"})
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"user"})
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orderHeaders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @Groups({"order"})
     * @ORM\OneToMany(targetEntity=OrderDetail::class, mappedBy="orderHeader", orphanRemoval=true)
     *
     */
    private $orderDetails;
    /**
     * @Groups({"order"})
     * @ORM\Column(type="decimal", precision=8, scale=2)
     */
    private $totalPrice;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $status;

    /**
     * @Groups({"order"})
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @Groups({"order"})
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
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

    /**
     * @return Collection|OrderDetail[]
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    public function addOrderDetails(OrderDetail $orderDetail): self
    {
        if (!$this->orderDetails->contains($orderDetail)) {
            $this->orderDetails[] = $orderDetail;
            $orderDetail->setOrderHeader($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetail $orderDetail): self
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getOrderHeader() === $this) {
                $orderDetail->setOrderHeader(null);
            }
        }

        return $this;
    }

    public function getTotalPrice(): ?string
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(string $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?int
    {
        return $this->createdAt->getTimestamp();
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?int
    {
        return $this->updatedAt->getTimestamp();
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }




}
