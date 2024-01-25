<?php

namespace App\Http\Controllers\Tenant\Contacts;

use App\Http\Controllers\Controller;


class PersonController extends Controller
{
    public function index()
    {
        return view('application.view.index.blade.php');
    }
}
