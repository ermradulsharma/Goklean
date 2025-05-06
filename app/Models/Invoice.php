<?php

namespace App\Models;

use App\Filters\QueryFilter;
use App\Traits\SecureDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\SchemalessAttributes\SchemalessAttributesTrait;

class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;
    use SecureDeletes;
    use SchemalessAttributesTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'invoices';
    protected $guarded = [];
    protected $fillable = [
        'customer_id',
        'customer_car_id',
        'order_type',
        'due_date',
        'payment_date',
        'payment_mode',
        'reference_id',
        'transaction_id',
        'status',
        'booking_completed',
        'billing_cycle_starts',
        'billing_cycle_ends',
        'subscription_id',
        'deleted_at'
    ];
    protected $casts = ['is_active' => 'boolean', 'booking_completed' => 'boolean'];
    protected $schemalessAttributes = ['data', 'preferences'];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */



    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function items()
    {
        return $this->belongsToMany(Product::class, 'invoice_items', 'invoice_id', 'product_id')->withPivot(['qty', 'price', 'sub_total']);
    }

    public function customerCar()
    {
        return $this->belongsTo(CustomerCar::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
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

    public function scopeWithDataAttributes(): Builder
    {
        return $this->data->modelScope();
    }

    public function scopeWithPreferencesAttributes(): Builder
    {
        return $this->preferences->modelScope();
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
