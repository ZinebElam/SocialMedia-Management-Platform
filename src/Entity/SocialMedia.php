<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * SocialMedia
 *
 * @ORM\Table(name="social_media")
 * @ORM\Entity
 */
class SocialMedia
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $clientId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $clientsecret;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    public function setClientId(int $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getClientsecret(): ?string
    {
        return $this->clientsecret;
    }

    public function setClientsecret(string $clientsecret): self
    {
        $this->clientsecret = $clientsecret;

        return $this;
    }
    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }
}
