<?php

namespace App\Entity;

use App\Repository\LieuRepository;
//use Doctrine\ORM\Mapping\ClassMetadataInfo;
//use ApiPlatform\Doctrine\Orm\Extension\EagerLoadingExtension;
//use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
//use ApiPlatform\Metadata\ApiFilter;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\ConferenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\SluggerInterface;

#[ORM\Entity(repositoryClass: LieuRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'lieu:item']),
        new GetCollection(normalizationContext: ['groups' => 'lieu:list'])
    ],
    order: ['year' => 'DESC', 'city' => 'ASC'],
    paginationEnabled: false,
)]
class Lieu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['lieu:list', 'lieu:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['lieu:list', 'lieu:item'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Groups(['lieu:list', 'lieu:item'])]
    private ?string $rue = null;

    #[ORM\Column]
    #[Groups(['lieu:list', 'lieu:item'])]
    private ?float $latitude = null;

    #[ORM\Column]
    #[Groups(['lieu:list', 'lieu:item'])]
    private ?float $longitude = null;

    #[ORM\ManyToOne(inversedBy: 'lieux')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['lieu:list', 'lieu:item'])]
    private ?Ville $ville = null;

    #[ORM\OneToMany(targetEntity: Sortie::class, mappedBy: 'lieu')]
    private Collection $sorties;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->nom;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): static
    {
        $this->rue = $rue;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSortie(Sortie $sortie): static
    {
        if (!$this->sorties->contains($sortie)) {
            $this->sorties->add($sortie);
            $sortie->setLieu($this);
        }

        return $this;
    }

    public function removeSortie(Sortie $sortie): static
    {
        if ($this->sorties->removeElement($sortie)) {
            // set the owning side to null (unless already changed)
            if ($sortie->getLieu() === $this) {
                $sortie->setLieu(null);
            }
        }

        return $this;
    }
}
