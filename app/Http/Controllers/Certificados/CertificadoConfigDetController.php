<?php

namespace App\Http\Controllers\Certificados;

use App\Http\Controllers\Controller;
use App\Models\Certificados\CertificadoConfigDet;

class CertificadoConfigDetController extends Controller
{
    public function index()
    {
       $configDets=CertificadoConfigDet::orderBy('ID', 'asc')->paginate(10);
       //print_r($configDets);
       return view('Certificados\certificadoConfigDets', compact('configDets'));
    }
}