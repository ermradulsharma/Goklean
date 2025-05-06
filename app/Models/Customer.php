<?php

namespace App\Models;

use App\Filters\QueryFilter;
use App\Scopes\CustomerScope;
use App\Traits\SecureDeletes;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Interfaces\WalletFloat;
use Bavix\Wallet\Traits\HasWalletFloat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Sanctum\HasApiTokens;
use Spatie\SchemalessAttributes\SchemalessAttributesTrait;

class Customer extends Model implements Wallet, WalletFloat
{
    use HasFactory;
    use SoftDeletes;
    use SecureDeletes;
    use SchemalessAttributesTrait;
    use HasWalletFloat;
    use HasApiTokens;

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

    protected $schemalessAttributes = [
        'preferences'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::addGlobalScope(new CustomerScope);
        static::created(function ($customer) {
            $customer->unique_code = $customer->unique;
            $customer->save();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function cars()
    {
        return $this->hasMany(CustomerCar::class, 'customer_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function addressChangeRequests()
    {
        return $this->hasMany(AddressChangeRequest::class, 'user_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'customer_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'customer_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'customer_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }

    public function userGroup()
    {
        return $this->belongsTo(UserGroup::class);
    }

    public function serviceUnit()
    {
        return $this->belongsTo(ServiceUnit::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeWithPreferencesAttributes(): Builder
    {
        return $this->preferences->modelScope();
    }

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
        return str_pad($code, 4, "0", STR_PAD_LEFT).str_pad($this->id, 6, "0", STR_PAD_LEFT);
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
