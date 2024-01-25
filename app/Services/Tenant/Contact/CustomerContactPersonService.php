<?php


namespace App\Services\Tenant\Contact;


use App\Models\Tenant\Customer\ContactPerson;
use App\Services\Tenant\TenantService;

class CustomerContactPersonService extends TenantService
{
    public function __construct(ContactPerson $contactperson)
    {
        $this->model = $contactperson;
    }

    public function update()
    {
        $this->model->fill($this->getAttributes());

        $this->model->save();

        return $this;
    }

    public function findCustomer($customer)
    {
        return $this->model->where('customer_id', $customer)->get();
    }

}