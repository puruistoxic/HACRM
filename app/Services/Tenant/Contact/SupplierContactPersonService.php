<?php

namespace App\Services\Tenant\Contact;

use App\Models\Tenant\Customer\ContactPerson;
use App\Services\Tenant\TenantService;

class SupplierContactPersonService  extends TenantService
{
    public function __construct(ContactPerson $contactperson)
    {
        $this->model = $contactperson;
    }

    public function update(): SupplierContactPersonService
    {
        $this->model->query()
            ->where('id', $this->getAttribute('id'))
            ->update($this->getAttributes());

        return $this; 
    }

    public function findSupplier($supplier)
    {
        return $this->model->where('supplier_id', $supplier)->get();
    }

}