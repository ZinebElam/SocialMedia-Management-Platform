<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
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
     * @ORM\Column(name="propositionComment", type="text", length=0, nullable=false)
     */
    private $propositionComment;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \Proposition
     *
     * @ORM\ManyToOne(targetEntity="Proposition")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="proposition_id", referencedColumnName="id")
     * })
     */
    private $proposition;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getPropositionComment(): ?string
    {
        return $this->propositionComment;
    }

    public function setPropositionComment(string $propositionComment): self
    {
        $this->propositionComment = $propositionComment;

        return $this;
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
    public function getProposition(): ?Proposition
    {
        return $this->proposition;
    }

    public function setProposition(?Proposition $proposition): self
    {
        $this->proposition = $proposition;

        return $this;
    }
}
