<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TarifTindakanController extends Controller
{
    // Index
    public function index()
    {
        $data = [
            'title' => 'Tarif Tindakan',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Tarif Tindakan',
        ];
        return view('master-data.tarif.tarif-tindakan', $data);
    }

    // Index
    public function form_tarif()
    {
        $data = [
            'title' => 'Tarif Tindakan',
            'menuTitle' => 'Master Data',
            'menuSubtitle' => 'Form Tambah Tarif',
        ];
        return view('master-data.tarif.form-tarif-tindakan', $data);
    }
}
