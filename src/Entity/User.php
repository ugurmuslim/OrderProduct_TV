<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="`user`")
 */
class User
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
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"user"})
     * @ORM\Column(type="string", length=50)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $apiKey;

    /**
     * @ORM\Column(type="string", length=120)
     */
    private $secret_key;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=OrderHeader::class, mappedBy="user")
     */
    private $orderHeaders;

    public function __construct()
    {
        $this->orderHeaders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getSecretKey(): ?string
    {
        return $this->secret_key;
    }

    public function setSecretKey(string $secret_key): self
    {
        $this->secret_key = $secret_key;

        return $this;
    }

    /**
     * @return Collection|OrderHeader[]
     */
    public function getOrderHeaders(): Collection
    {
        return $this->orderHeaders;
    }

    public function addOrderHeader(OrderHeader $orderHeader): self
    {
        if (!$this->orderHeaders->contains($orderHeader)) {
            $this->orderHeaders[] = $orderHeader;
            $orderHeader->setUser($this);
        }

        return $this;
    }

    public function removeOrderHeader(OrderHeader $orderHeader): self
    {
        if ($this->orderHeaders->removeElement($orderHeader)) {
            // set the owning side to null (unless already changed)
            if ($orderHeader->getUser() === $this) {
                $orderHeader->setUser(null);
            }
        }

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
