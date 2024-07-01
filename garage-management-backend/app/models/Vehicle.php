<?php

namespace Models;

class Vehicle {
    public int $id;
    public string $license_plate;
    public string $vehicle_type;
    public int $user_id;
    public string $check_in_time; 
    public string $check_out_time;
    public bool $is_checked_in;
}
