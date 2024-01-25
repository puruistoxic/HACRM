<?php

namespace App\Filters\Tenant;

use App\Filters\Core\traits\NameFilter;
use App\Filters\Traits\DateRangeFilter;
use App\Filters\Core\traits\SearchFilterTrait;
use App\Filters\FilterBuilder;
use App\Filters\Traits\SearchByNameFilterTrait;
use Illuminate\Database\Eloquent\Builder;

class ContactPersonFilter extends FilterBuilder
{
    use SearchFilterTrait, NameFilter, SearchByNameFilterTrait;

    // public function contactperson($value = null)
    // {
    //     $this->builder->where('id', $value);
    // }


    // // public function search($value = null)
    // // {
    // //     $this->builder->where(function (Builder $builder) use ($value) {
    // //         $builder->whereRaw("CONCAT(first_name,' ',last_name) LIKE ?", "%{$value}%")
    // //             ->orWhere('tin', $value)
    // //             ->orWhereHas('emails', function (Builder $builder) use ($value) {
    // //                 $builder->where('value', $value);
    // //             })
    // //             ->orWhereHas('phoneNumbers', function (Builder $builder) use ($value) {
    // //                 $builder->where('value', $value);
    // //             });
    // //     });
    // // }

    public function customerGroups($id = null)
    {
        $ids = explode(',', $id);

        $this->builder->when($id, function (Builder $builder) use ($ids) {
            $builder->whereIn('customer_group_id', $ids);
        });

    }

    // public function contactPersonGroups($id = null)
    // {
    //     $ids = explode(',', $id);

    //     $this->builder->when($id, function (Builder $builder) use ($ids) {
    //         $builder->whereIn('customer_group_id', $ids);
    //     });
    // }

    public function contactPersons($id = null)
    {
        $ids = explode(',', $id);

        $this->builder->when($id, function (Builder $builder) use ($ids) {
            $builder->whereIn('customer_id', $ids);
        });
    }
}
