<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\DragonTreasureRepository;
use Carbon\Carbon;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: DragonTreasureRepository::class)]
#[ApiResource(
    shortName: 'Treasure',
    description: 'Ceci est la description générale',
    paginationItemsPerPage: 10,
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Put(),
        new Patch(),
    ],
    normalizationContext: [
        'groups' => ['treasure:read'],
    ],
    denormalizationContext: [
        'groups' => ['treasure:write'],
    ]
)]

class DragonTreasure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * C'est le nom du dragon
     */
    #[ORM\Column(length: 255)]
    #[Groups(['treasure:read', 'treasure:write'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['treasure:read'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['treasure:read', 'treasure:write'])]
    private ?int $value = null;

    #[ORM\Column]
    #[Groups(['treasure:read', 'treasure:write'])]
    private ?int $coolFactor = null;

    /**
     * C'est la date de création
     */
    #[ORM\Column]
    private ?\DateTimeImmutable $plunderedAt;

    #[ORM\Column]
    private ?bool $isPublished = false;

    public  function __construct(string  $name)
    {
        $this->name = $name;
        $this->plunderedAt = new \DateTimeImmutable();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

/*    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }*/

    public function getDescription(): ?string
    {
        return $this->description;
    }

    #[SerializedName('description')]
    #[Groups(['treasure:write'])]
    public function setDescription(string $description): self
    {
        $this->description = nl2br($description);

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getCoolFactor(): ?int
    {
        return $this->coolFactor;
    }

    public function setCoolFactor(int $coolFactor): static
    {
        $this->coolFactor = $coolFactor;

        return $this;
    }

    #[Groups(['treasure:read'])]
    public function getPlunderedAt(): ?\DateTimeImmutable
    {
        return $this->plunderedAt;
    }

    public function setPlunderedAt(\DateTimeImmutable $plunderedAt): self
    {
        $this->plunderedAt = $plunderedAt;

        return $this;
    }

    /**
     * A human representation for date created
     */
    public function getPlunderedAtAgo(): string
    {
        return Carbon::instance($this->plunderedAt)->diffForHumans();
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }
}
