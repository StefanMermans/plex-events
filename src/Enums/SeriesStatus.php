<?php

namespace App\Enums;

enum SeriesStatus: string
{
    case Ongoing = 'ongoing';
    case Ended = 'ended';
    case Upcoming = 'upcoming';
    case Unknown = 'unknown';
}
