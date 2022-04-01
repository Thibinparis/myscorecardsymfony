<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $email;

    #[ORM\Column(type: 'string', length: 20)]
    private $nickname;

    #[ORM\OneToMany(mappedBy: 'winner_id', targetEntity: Contest::class)]
    private $yes;

    #[ORM\ManyToMany(targetEntity: Contest::class, mappedBy: 'players')]
    private $contests;

    #[ORM\OneToOne(mappedBy: 'player', targetEntity: User::class, cascade: ['persist', 'remove'])]
    private $user;

    public function __construct()
    {
        $this->yes = new ArrayCollection();
        $this->contests = new ArrayCollection();
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

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * @return Collection<int, Contest>
     */
    public function getYes(): Collection
    {
        return $this->yes;
    }

    public function addYe(Contest $ye): self
    {
        if (!$this->yes->contains($ye)) {
            $this->yes[] = $ye;
            $ye->setWinnerId($this);
        }

        return $this;
    }

    public function removeYe(Contest $ye): self
    {
        if ($this->yes->removeElement($ye)) {
            // set the owning side to null (unless already changed)
            if ($ye->getWinnerId() === $this) {
                $ye->setWinnerId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Contest>
     */
    public function getContests(): Collection
    {
        return $this->contests;
    }

    public function addContest(Contest $contest): self
    {
        if (!$this->contests->contains($contest)) {
            $this->contests[] = $contest;
            $contest->addPlayer($this);
        }

        return $this;
    }

    public function removeContest(Contest $contest): self
    {
        if ($this->contests->removeElement($contest)) {
            $contest->removePlayer($this);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setPlayer(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getPlayer() !== $this) {
            $user->setPlayer($this);
        }

        $this->user = $user;

        return $this;
    }
}
