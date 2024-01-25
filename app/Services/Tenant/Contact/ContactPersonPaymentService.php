<?php


namespace App\Services\Tenant\Contact;


use App\Models\Tenant\Customer\Payment;
use App\Services\Tenant\TenantService;

class ContactPersonPaymentService extends TenantService
{
    public function __construct(Payment $payment)
    {
        $this->model = $payment;
    }

    public function update()
    {
        $this->model->fill($this->getAttributes());

        $this->model->save();

        return $this;
    }

    public function findPayment($contactperson)
    {
        return $this->model->where('contact_person_id', $contactperson)->get();
    }

    public function findPaymentId($contactperson)
    {
        return $this->model->where('id', $contactperson)->first();
    }

}