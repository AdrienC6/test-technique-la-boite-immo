<?php

namespace App\Entity;

use App\Enum\ExportStatus;
use App\Repository\ExportRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: ExportRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Export implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'exports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Property $property = null;

    #[ORM\ManyToOne(inversedBy: 'exports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Gateway $gateway = null;

    #[ORM\Column(type: 'string', enumType: ExportStatus::class)]
    private ?ExportStatus $status = ExportStatus::PENDING;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $externalId = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $response = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProperty(): ?Property
    {
        return $this->property;
    }

    public function setProperty(?Property $property): static
    {
        $this->property = $property;

        return $this;
    }

    public function getGateway(): ?Gateway
    {
        return $this->gateway;
    }

    public function setGateway(?Gateway $gateway): static
    {
        $this->gateway = $gateway;

        return $this;
    }

    public function getStatus(): ?ExportStatus
    {
        return $this->status;
    }

    public function setStatus(ExportStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(string $externalId): static
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function getResponse(): ?array
    {
        return $this->response;
    }

    public function setResponse(?array $response): static
    {
        $this->response = $response;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'property' => $this->getProperty(),
            'gateway' => $this->getGateway(),
            'status' => $this->getStatus()?->value,
            'externalId' => $this->getExternalId(),
            'response' => $this->getResponse(),
            'createdAt' => $this->getCreatedAt()?->format(\DateTime::ATOM),
            'updatedAt' => $this->getUpdatedAt()?->format(\DateTime::ATOM),
        ];
    }
}
