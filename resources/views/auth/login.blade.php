@extends('layout.auth')

@section('title', trans('default.login'))

@section('contents')
    @php
        ['logo' => $logo] = \App\Http\Composer\Helper\LogoIcon::new(true)->logoIcon();
    @endphp

    <app-auth-login
            app-name="{{ settings('company_name') }}"
            market-place-version="{{env('MARKET_PLACE_VERSION')}}">
    </app-auth-login>
    <!-- <app-auth-login
            logo-url="https://crm.thehopeandglory.com/CompanyLogo/b0b160dc-cd29-43b0-b61c-b0679d28456a..png"
            app-name="{{ settings('company_name') }}"
            market-place-version="{{env('MARKET_PLACE_VERSION')}}">
    </app-auth-login> -->

@endsection

