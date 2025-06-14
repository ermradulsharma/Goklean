<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class carServiceRecords extends Model
{
    use HasFactory;
    protected $table = 'car_service_records';
    protected $fillable = [
        'service_unit_id',
        'customer_id',
        'booking_id',
        'type',
        'notes',
    ];
    public function images()
    {
        return $this->hasMany(carServiceImage::class, 'car_service_record_id');
    }

    public function serviceUnit()
    {
        return $this->belongsTo(User::class, 'service_unit_id')->select(['id', 'unique_code', 'first_name', 'last_name', 'email', 'mobile']);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id')->select(['id', 'unique_code', 'first_name', 'last_name', 'email', 'mobile']);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id')->with('invoice');
    }
}
