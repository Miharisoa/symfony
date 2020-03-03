<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TopicRepository")
 */
class Topic
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="topics")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateurId;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UtilisateurVuTopic", mappedBy="topicId", orphanRemoval=true)
     */
    private $utilisateurVuTopics;

    public function __construct()
    {
        $this->utilisateurVuTopics = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getUtilisateurId(): ?User
    {
        return $this->utilisateurId;
    }

    public function setUtilisateurId(?User $utilisateurId): self
    {
        $this->utilisateurId = $utilisateurId;

        return $this;
    }

    /**
     * @return Collection|UtilisateurVuTopic[]
     */
    public function getUtilisateurVuTopics(): Collection
    {
        return $this->utilisateurVuTopics;
    }

    public function addUtilisateurVuTopic(UtilisateurVuTopic $utilisateurVuTopic): self
    {
        if (!$this->utilisateurVuTopics->contains($utilisateurVuTopic)) {
            $this->utilisateurVuTopics[] = $utilisateurVuTopic;
            $utilisateurVuTopic->setTopicId($this);
        }

        return $this;
    }

    public function removeUtilisateurVuTopic(UtilisateurVuTopic $utilisateurVuTopic): self
    {
        if ($this->utilisateurVuTopics->contains($utilisateurVuTopic)) {
            $this->utilisateurVuTopics->removeElement($utilisateurVuTopic);
            // set the owning side to null (unless already changed)
            if ($utilisateurVuTopic->getTopicId() === $this) {
                $utilisateurVuTopic->setTopicId(null);
            }
        }

        return $this;
    }
}
