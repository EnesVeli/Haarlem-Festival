<?php

namespace App\Enums;

enum BookingType : int{
    case History = 0;
    case Stories = 1;
    case Yummy = 2;
    case Jazz = 3;
    case Dance = 4;
}