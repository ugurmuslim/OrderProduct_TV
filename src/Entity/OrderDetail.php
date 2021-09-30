<?php

namespace App\Entity;

use App\Repository\OrderDetailRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass=OrderDetailRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class OrderDetail
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
     * @Groups({"order_detail"})
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=orderHeader::class)
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $orderHeader;

    /**
     * @Groups({"order_detail"})
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @ORM\JoinColumn(nullable=false)
     */
    #[MaxDepth(2)]
    private $product;

    /**
     * @Groups({"order_detail"})
     * @ORM\Column(type="decimal", precision=6, scale=2)
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderHeader(): ?orderHeader
    {
        return $this->orderHeader;
    }

    public function setOrderHeader(?orderHeader $OrderHeader): self
    {
        $this->orderHeader = $OrderHeader;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCreatedAt(): ? string
    {
        return $this->createdAt->getTimestamp();
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt->getTimestamp();
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


}
