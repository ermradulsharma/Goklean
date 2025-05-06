<?php

namespace App\Models;

use App\Filters\QueryFilter;
use App\Scopes\ServiceUnitScope;
use App\Traits\SecureDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceUnit extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SecureDeletes;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'users';

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::addGlobalScope(new ServiceUnitScope);
        static::created(function ($unit) {
            $unit->unique_code = $unit->unique;
            $unit->save();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function bookings()
    {
        return $this->hasMany(Schedule::class, 'service_unit_id');
    }

    public function bookingSessions()
    {
        return $this->hasMany(Booking::class, 'service_unit_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }

    public function scopeActive($query)
    {
        $query->where('is_active', true);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute($value)
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getUniqueAttribute()
    {
        $code = isset($this->city->code) ? strtoupper($this->city->code) : null;
        return str_pad($code, 4, "0", STR_PAD_LEFT) . str_pad($this->id, 6, "0", STR_PAD_LEFT);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
