<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fahrzeuge;
use App\Models\Auftraege;

class DashboardController extends Controller{
    
    public function index(){
        $fahrzeuge = Fahrzeuge::all(); // Routen abrufen
        $auftraege = Auftraege::all();
        return view('pages.index', compact('fahrzeuge', 'auftraege'));
    }

}