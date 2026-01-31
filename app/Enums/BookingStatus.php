<?php

namespace App\Enums;

enum BookingStatus: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case DECLINED = 'declined';
    case COMPLETED = 'completed';
}
