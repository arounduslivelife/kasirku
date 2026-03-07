<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\Pengeluaran;
use App\Models\Invin;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = date('Y-m-d');
        
        // Stats
        $omzetHariIni = Penjualan::where('tanggal', $today)->sum('total_harga');
        $trxHariIni = Penjualan::where('tanggal', $today)->count();
        
        $totalHppHariIni = DB::table('penjualan_detail')
            ->join('penjualan', 'penjualan.id', '=', 'penjualan_detail.penjualan_id')
            ->where('penjualan.tanggal', $today)
            ->sum(DB::raw('penjualan_detail.hpp * penjualan_detail.qty'));
            
        $biayaHariIni = Pengeluaran::where('tanggal', $today)->sum('nominal');
        $profitHariIni = $omzetHariIni - $totalHppHariIni - $biayaHariIni;

        // Chart Data (Last 7 Days)
        $chartData = Penjualan::select(DB::raw('tanggal, sum(total_harga) as total'))
            ->where('tanggal', '>', date('Y-m-d', strtotime('-7 days')))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Stock Critical
        $criticalStocks = Produk::where('jumlah', '<=', 5)->orderBy('jumlah', 'asc')->take(5)->get();

        // Recent Transactions
        $recentSales = Penjualan::with('user')->latest()->take(5)->get();

        return view('dashboard', compact(
            'omzetHariIni', 'trxHariIni', 'profitHariIni', 
            'chartData', 'criticalStocks', 'recentSales'
        ));
    }
}
