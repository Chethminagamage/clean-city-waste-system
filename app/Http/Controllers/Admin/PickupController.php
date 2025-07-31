<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PickupController extends Controller
{
    public function index()
    {
        return view('admin.pickups'); // Blade: resources/views/admin/pickups.blade.php
    }
}
