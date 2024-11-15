<?php

namespace App\Entity\Enum;

enum TicketType: string
{
    case adult = 'adult';
    case kid = 'kid';
    case group = 'group';
    case discount = 'discount';
}
