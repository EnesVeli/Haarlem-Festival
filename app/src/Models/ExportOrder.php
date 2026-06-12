<?php

namespace App\Models;

use App\ViewModels\OrderCmsExportParam;

class ExportOrder{
    public ?int $order_id;  
    public ?string $date;
    public ?int $status;
    public ?int $total_price;

    public ?int $user_id;
    public ?string $email;
    public ?string $name;

    public function getArrayValuesForCSV(OrderCmsExportParam $param) : array {
        $vals = [];

        if($param->user_id)     array_push($vals, $this->user_id);
        if($param->user_email)  array_push($vals, $this->email);
        if($param->user_name)   array_push($vals, $this->name);
        if($param->order_id)    array_push($vals, $this->order_id);
        if($param->order_date)  array_push($vals, $this->date);
        if($param->status)      array_push($vals, $this->status === 1 ? 'Not-Paid' : ($this->status === 2 ? 'Paid' : 'Cancelled'));
        if($param->total_price) array_push($vals, number_format($this->total_price / 100, 2, ',', ''));

        return $vals;
    }
}