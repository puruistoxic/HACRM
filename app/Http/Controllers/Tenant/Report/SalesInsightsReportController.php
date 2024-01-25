<?php

namespace App\Http\Controllers\Tenant\Report;

use App\Http\Controllers\Controller;


class SalesInsightsController extends Controller
{
    public function index()
    {
        return view('application.view.index.blade.php');
    }
}
