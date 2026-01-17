<?php

namespace App\Models;

use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ServiceComplaintImage extends Model
{
    use HasFactory, HasImage;
    protected $table = 'service_complaints_images';
    protected $fillable = [
        'service_complaint_id',
        'image_path'
    ];
    protected $appends = [
        'url',
    ];
    public function getUrlAttribute($value)
    {
        return $this->image_path ? Storage::url($this->image_path) : '';
    }
}
