<?php


namespace App\Enums;

enum TicketStatus: String
{

    case OPEN= 'open';

    case RESOLVED= 'resolved';



    case REJECTED= 'rejected';
}