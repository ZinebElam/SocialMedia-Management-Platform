<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=false)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=false)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resettoken;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $role = [];

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="SocialMedia", mappedBy="user")
     */
    private $socialMedia;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Proposition", mappedBy="userSubmit")
     */
    private $propositions;


    public function __construct()
    {
        $this->role = array('ROLE_USER');
        $this->socialMedia = new ArrayCollection();
        $this->propositions = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getResettoken(): ?string
    {
        return $this->resettoken;
    }

    public function setResettoken(?string $resettoken): self
    {
        $this->resettoken = $resettoken;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->role;
    }

    public function setRole(?array $role): self
    {
        $this->role = $role;

        return $this;
    }
    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->email;
    }
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Collection|Proposition[]
     */
    public function getPropositions(): Collection
    {
        return $this->propositions;
    }

    public function addProposition(Proposition $proposition): self
    {
        if (!$this->propositions->contains($proposition)) {
            $this->propositions[] = $proposition;
            $proposition->setUserSubmit($this);
        }

        return $this;
    }

    public function removeProposition(Proposition $proposition): self
    {
        if ($this->propositions->contains($proposition)) {
            $this->propositions->removeElement($proposition);
            // set the owning side to null (unless already changed)
            if ($proposition->getUserSubmit() === $this) {
                $proposition->setUserSubmit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|SocialMedia[]
     */
    public function getSocialMedia(): Collection
    {
        return $this->socialMedia;
    }

    public function addSocialMedia(SocialMedia $socialMedia): self
    {
        if (!$this->socialMedia->contains($socialMedia)) {
            $this->socialMedia[] = $socialMedia;
            $socialMedia->addUser($this);
        }

        return $this;
    }

    public function removeSocialMedia(SocialMedia $socialMedia): self
    {
        if ($this->socialMedia->contains($socialMedia)) {
            $this->socialMedia->removeElement($socialMedia);
            $socialMedia->removeUser($this);
        }

        return $this;
    }
}