<?php

namespace App\Http\Controllers\Tenant\Report;

use App\Models\Tenant\Report\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AllReportController extends Controller
{
    public function index()
    {
        // Fetch all reports from the database
        $reports = Report::all();

        // Return the data, for example, as JSON
        return response()->json($reports);
    }


    public function reportDetails($reportName)
    {
        // Find the report by report_name
        $report = Report::where('report_name', $reportName)->first();

        // Check if the report exists
        if ($report) {
            // Return the report data as JSON
            return response()->json(['data' => $report], 200);
        } else {
            // Report not found
            return response()->json(['error' => 'Report not found'], 404);
        }
    }
}
