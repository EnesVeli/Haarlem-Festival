<?php

namespace App\ViewModels;

class OrderCmsExportParam{
    public bool $user_id;
    public bool $user_email;
    public bool $user_name;
    public bool $order_id;
    public bool $order_date;
    public bool $total_price;
    public bool $status;
    public ?bool $status_1 = null;
    public ?bool $status_2 = null;
    public ?bool $status_3 = null;

    public function isSet() : bool {
        if(!isset($this->user_id)) return false;
        if(!isset($this->user_email)) return false;
        if(!isset($this->user_name)) return false;
        if(!isset($this->order_id)) return false;
        if(!isset($this->order_date)) return false;
        if(!isset($this->total_price)) return false;
        if(!isset($this->status)) return false;
        
        if($this->status){
            if(!isset($this->status_1)) return false;
            if(!isset($this->status_2)) return false;
            if(!isset($this->status_3)) return false;
        }

        return true;
    }

    public function isSetCorrectly() : bool {
        if(!$this->isSet()) return false;

        if($this->status){
            if(!$this->status_1 && !$this->status_2 && !$this->status_3) return false;
            else return true;
        }

        if($this->total_price || $this->order_id || $this->order_date || $this->user_id || $this->user_email || $this->user_name) {
            return true;
        }

        return false;
    }

    public function getQueryOrderArguments() : array {
        $args = [];

        if($this->order_id) array_push($args, 'order_id');
        if($this->user_id) array_push($args, 'user_id');
        if($this->order_date) array_push($args, 'date');
        if($this->total_price) array_push($args, 'total_price');
        if($this->status) array_push($args, 'status');

        return $args;
    }

    public function getQueryUserArguments() : array {
        $args = [];

        if($this->user_email) array_push($args, 'email');
        if($this->user_name) array_push($args, 'name');

        return $args;
    }

    public function getAllArgumentsForHeader() : array {
        $args = [];

        if($this->user_id) array_push($args, 'user_id');
        if($this->user_email) array_push($args, 'user_email');
        if($this->user_name) array_push($args, 'user_name');

        if($this->order_id) array_push($args, 'order_id');
        if($this->order_date) array_push($args, 'order_date');  
        if($this->status) array_push($args, 'order_status');
        if($this->total_price) array_push($args, 'total_price');  

        return $args;
    }

    public function getQueryExcludedStatuses() : array {
        $args = [];

        if(!$this->status_1) array_push($args, '1');
        if(!$this->status_2) array_push($args, '2');
        if(!$this->status_3) array_push($args, '3');

        return $args;
    }
}