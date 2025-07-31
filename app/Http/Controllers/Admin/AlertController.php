<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index()
    {
        return view('admin.alerts'); // Corresponds to: resources/views/admin/alerts.blade.php
    }
}
