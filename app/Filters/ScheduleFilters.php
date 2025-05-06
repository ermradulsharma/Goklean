<?php


namespace App\Filters;


use Carbon\Carbon;

class ScheduleFilters extends QueryFilter
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

    public function range($range = [])
    {
        if($range['start'] !== null && $range['end'] !== null) {
            $from = Carbon::parse($range['start'])->startOfDay()->toDateTimeString();
            $to = Carbon::parse($range['end'])->endOfDay()->toDateTimeString();

            return $this->builder->whereBetween('date', [$from, $to])->get();
        }
        return null;
    }

    public function service_unit($query = null)
    {
        return $this->builder->where('service_unit_id', '=', $query);
    }

    public function status($query = null)
    {
        return $this->builder->where('status', '=', $query);
    }
}
