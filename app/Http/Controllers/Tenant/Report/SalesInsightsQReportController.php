<?php

namespace App\Http\Controllers\Tenant\Report;

use App\Http\Controllers\Controller;


class SalesInsightsQController extends Controller
{
    public function index()
    {
        return view('application.view.index.blade.php');
    }
}
