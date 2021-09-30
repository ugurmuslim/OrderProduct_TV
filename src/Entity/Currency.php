<?php

namespace App\Entity;

use App\Repository\CurrencyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CurrencyRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="`currency`")
 */
class Currency
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
     * @ORM\Column(type="string",length=10)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $Title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

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
