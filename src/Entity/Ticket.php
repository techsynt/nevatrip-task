<?php

namespace App\Entity;

use App\Entity\Enum\TicketType;
use App\Repository\TicketRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $ord_id = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(length: 255, enumType: TicketType::class)]
    private TicketType $type;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $barcode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrdId(): ?Order
    {
        return $this->ord_id;
    }

    public function setOrdId(?Order $ord_id): static
    {
        $this->ord_id = $ord_id;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getType(): TicketType
    {
        return $this->type;
    }

    public function setType(TicketType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(string $barcode): static
    {
        $this->barcode = $barcode;

        return $this;
    }
}
