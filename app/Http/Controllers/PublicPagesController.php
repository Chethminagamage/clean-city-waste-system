<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicPagesController extends Controller
{
    /**
     * Display the services page
     */
    public function services()
    {
        return view('public.services');
    }

    /**
     * Display the projects page
     */
    public function projects()
    {
        return view('public.projects');
    }

    /**
     * Display the company page
     */
    public function company()
    {
        return view('public.company');
    }

    /**
     * Display the blog page
     */
    public function blog()
    {
        return view('public.blog');
    }

    /**
     * Display the contact page
     */
    public function contact()
    {
        return view('public.contact');
    }
}
