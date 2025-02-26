<?php

namespace App\Http\Controllers\Tarif;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SKTarifController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'SK Tarif',
            'menuTitle' => 'Tarif',
            'menuSubtitle' => 'SK Tarif',
        ];
        return view('Tarif.SKTarif.sk-tarif', $data);
    }
}
