<?php

namespace App\Entity;

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
}
