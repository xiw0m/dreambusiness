<?php

class Customer {
    public $customer_id;
    public $pet_name;
    public $pet_birthday;
    public $phone_number;

    public function __construct($customer_id, $pet_name, $pet_birthday, $phone_number) {
        $this->customer_id = $customer_id;
        $this->pet_name = $pet_name;
        $this->pet_birthday = $pet_birthday;
        $this->phone_number = $phone_number;
    }
}

class GroomingSession {
    public $session_id;
    public $customer_id;
    public $date_of_grooming;
    public $total_price;

    public function __construct($session_id, $customer_id, $date_of_grooming, $total_price) {
        $this->session_id = $session_id;
        $this->customer_id = $customer_id;
        $this->date_of_grooming = $date_of_grooming;
        $this->total_price = $total_price;
    }
}