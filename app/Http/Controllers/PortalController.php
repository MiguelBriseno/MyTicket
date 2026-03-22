<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class PortalController extends Controller
{
    public function index()
    {
        return redirect()->route('portal.tickets');
    }

    public function tickets()
    {
        return view('portal.tickets');
    }

    public function create()
    {
        return view('portal.create');
    }
}
