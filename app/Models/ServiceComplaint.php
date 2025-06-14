<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceComplaint extends Model
{
    use HasFactory;
    protected $table = 'service_complaints';
    protected $fillable = [
        'complaint_no',
        'customer_id',
        'booking_id',
        'service_unit_id',
        'subject',
        'description',
        'su_reply',
        'status'
    ];
    public function images()
    {
        return $this->hasMany(ServiceComplaintImage::class, 'service_complaints_id');
    }
}
