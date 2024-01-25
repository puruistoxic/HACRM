<?php


namespace App\Services\Tenant\Contact;


use App\Models\Tenant\Customer\Activity;
use App\Services\Tenant\TenantService;

class ContactPersonActivityService extends TenantService
{
    public function __construct(Activity $activity)
    {
        $this->model = $activity;
    }

    public function update()
    {
        $this->model->fill($this->getAttributes());

        $this->model->save();

        return $this;
    }

    public function findActivity($contactperson)
    {
        return $this->model->where('contact_person_id', $contactperson)->get();
    }

    public function findActivityId($contactperson)
    {
        return $this->model->where('id', $contactperson)->first();
    }

}