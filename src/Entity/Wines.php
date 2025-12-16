<?php

namespace App\Entity;

use App\Repository\WineRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WineRepository::class)]
#[ORM\Table(name: "vino")]
class Wines
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[Groups(['wine_details'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(name: "id_usuario", referencedColumnName: "id", nullable: false)]
    #[Groups(['wine_details'])]
    private Users $users;

    #[ORM\Column(name: "nombre", length: 50)]
    #[Groups(['wine_details'])]
    private ?string $name = null;

    #[ORM\Column(name: "año")]
    #[Groups(['wine_details'])]
    private ?int $year = null;

    #[ORM\OneToMany(targetEntity: Measurements::class, mappedBy: "wine")]
    #[Groups(['wine_details'])]
    private Collection $measurements;

    public function __construct(Collection $measurements)
    {
        $this->measurements = $measurements;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getUsers(): Users
    {
        return $this->users;
    }

    public function setUsers(Users $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getMeasurements(): Collection
    {
        return $this->measurements;
    }
}