<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;

class BinReportController extends Controller
{
    public function index()
    {
        $reports = Report::orderBy('submitted_at', 'desc')->paginate(10);
        return view('admin.bin_reports', compact('reports'));
    }
}
