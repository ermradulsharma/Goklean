<?php

namespace App\Models;

use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class carServiceImage extends Model
{
    use HasFactory, HasImage; 
    protected $table = 'car_service_images';
    protected $fillable = [
        'car_service_record_id',
        'wash_type',
        'image_type',
        'image_path',
        'image'
    ];
}
