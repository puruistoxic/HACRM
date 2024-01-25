<?php

namespace App\Filters\Tenant;

use App\Filters\FilterBuilder;
use App\Filters\Traits\DateRangeFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filters\Core\traits\FirstNameFilter;
use App\Filters\Core\traits\FirstSearchFilterTrait;
use App\Filters\Traits\SearchByFirstNameFilterTrait;

class CustomerFilter extends FilterBuilder
{
    use FirstSearchFilterTrait, FirstNameFilter, SearchByFirstNameFilterTrait;

    // public function customer($value = null)
    // {
    //     $this->builder->where('id', $value);
    // }


    // public function search($query, $value = null)
    // {
    //     // $this->builder->where(function (Builder $builder) use ($value) {
    //     //     $builder->whereRaw("first_name LIKE ?", "%{$value}%")
    //     //         ->orWhereHas('email', function (Builder $builder) use ($value) {
    //     //             $builder->where('value', $value);
    //     //         })
    //     //         ->orWhereHas('phone_number', function (Builder $builder) use ($value) {
    //     //             $builder->where('value', $value);
    //     //         });
    //     // });

    //     $query->where(function (Builder $builder) use ($value) {
    //         $builder->where('first_name', 'LIKE', "%{$value}%")
    //             ->orWhereHas('email', function (Builder $builder) use ($value) {
    //                 $builder->where('value', 'LIKE', "%{$value}%");
    //             })
    //             ->orWhereHas('phone_number', function (Builder $builder) use ($value) {
    //                 $builder->where('value', 'LIKE', "%{$value}%");
    //             });
    //     });
    // }

    public function customerGroups($id = null)
    {
        $ids = explode(',', $id);

        $this->builder->when($id, function (Builder $builder) use ($ids) {
            $builder->whereIn('customer_group_id', $ids);
        });
    }

    // public function addressArea($value = null)
    // {
    //     $this->builder->when($value, function (Builder $builder) use ($value) {
    //         $builder->whereHas('addresses', function ($q) use ($value) {
    //             $q->where('area', 'LIKE', $value);
    //         });
    //     });
    // }

    // public function addressCity($value = null)
    // {
    //     $this->builder->when($value, function (Builder $builder) use ($value) {
    //         $builder->whereHas('addresses', function ($q) use ($value) {
    //             $q->where('city', 'LIKE', $value);
    //         });
    //     });
    // }
}
