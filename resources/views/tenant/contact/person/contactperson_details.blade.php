@extends('layout.tenant')
@section('title', ucwords(__t('contact_person_details')))

@section('contents')
<app-contact-person-details :customer-id="{{ $customer_id }}"></app-contact-person-details>
@endsection