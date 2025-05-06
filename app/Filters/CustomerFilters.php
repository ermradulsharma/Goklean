<?php


namespace App\Filters;


class CustomerFilters extends QueryFilter
{
    /*
    |--------------------------------------------------------------------------
    | DEFINE ALL COLUMN FILTERS BELOW
    |--------------------------------------------------------------------------
    */

    public function name($query = "")
    {
        return $this->builder->where('first_name', 'like', '%'.$query.'%')
            ->orWhere('last_name', 'like', '%'.$query.'%');
    }

    public function code($query = "")
    {
        return $this->builder->where('unique_code', 'like', '%'.$query.'%');
    }

    public function email($query = "")
    {
        return $this->builder->where('email', 'like', '%'.$query.'%');
    }

    public function mobile($query = "")
    {
        return $this->builder->where('mobile', 'like', '%'.$query.'%');
    }

    public function status($query = 0)
    {
        return $this->builder->where('is_active', $query);
    }
}
