<?php


namespace App\Filters;


class CustomerCarFilters extends QueryFilter
{
    /*
    |--------------------------------------------------------------------------
    | DEFINE ALL COLUMN FILTERS BELOW
    |--------------------------------------------------------------------------
    */

    public function code($query = "")
    {
        return $this->builder->where('code', 'like', '%'.$query.'%');
    }

    public function customer($query = '')
    {
        if($query !== '') {
            return $this->builder->whereHas('customer', function ($q) use ($query) {
                $q->where('role', 'customer')
                    ->where('first_name', 'like', "%{$query}%")
                    ->orWhere('last_name', 'like', "%{$query}%");
            });
        }
        return null;
    }

    public function number($query = "")
    {
        return $this->builder->where('number_plate', 'like', '%'.$query.'%');
    }

    public function status($query = 0)
    {
        return $this->builder->where('is_active', $query);
    }
}
