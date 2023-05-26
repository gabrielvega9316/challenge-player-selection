<?php

namespace App\Enums;

enum PlayerPosition: string
{
    case Defense = 'defender';
    case Midfielder = 'midfielder';
    case Forward = 'forward';
}
