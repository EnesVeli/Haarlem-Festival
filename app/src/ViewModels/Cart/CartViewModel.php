<?php

namespace App\ViewModels\Cart;

use App\Models\Order;

class CartViewModel{
    public Order $order;

    public string $sub_total;
    public string $vat_cost;
    public string $vat_persent;
    public string $total;
}