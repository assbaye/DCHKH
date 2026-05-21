<?php

namespace App\Http\Controllers;

use App\Models\Reunion;

class ReunionController extends Controller
{
    public function index()
    {
        $reunions = Reunion::orderBy('date_reunion', 'desc')->paginate(15);
        return inertia('Reunions/Index', ['reunions' => $reunions]);
    }

    public function show(Reunion $reunion)
    {
        $reunion->load('membres');
        return inertia('Reunions/Show', ['reunion' => $reunion]);
    }
}
