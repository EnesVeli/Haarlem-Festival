<?php

namespace App\Enums;

enum OrderStatus : int {
    case InCart = 0;
    case NotPaid = 1;
    case Paid = 2;
    case Canceled = 3;
}