<?php

namespace App\Http\Controllers\Tenant\Contacts;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Customer\ContactPerson;

class ContactPersonDetailsController extends Controller
{

    public function index(ContactPerson $contactperson)
    {
        $contacts = $contactperson->contacts->groupBy('name');

        return $contactperson->setRelation('contacts', $contacts)->load(['customerGroup', 'addresses', 'profilePicture']);
    }
}
