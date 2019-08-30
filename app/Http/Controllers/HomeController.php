<?php

namespace App\Http\Controllers;

use App\Exports\WarehouseExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    public function index(){
        return redirect('/admin/login');
    }

    public function demoExportExcel(){
        return Excel::download(new WarehouseExport(), 'agencies.xlsx');
    }
}
