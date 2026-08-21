<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
class HomeController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::where('is_active', true)->get();
        return view('pages.home', compact('vouchers'));
    }
}
