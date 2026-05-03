<?php

namespace App\ViewModels\Cart;

use App\Models\Order;

class CartViewModel{
    public Order $order;

    public string $total;
}