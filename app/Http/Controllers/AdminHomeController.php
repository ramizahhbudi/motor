<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\ServiceSpecification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminHomeController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // Calculate stats
        $today = Carbon::today();
        $totalBookingsToday = Booking::whereDate('created_at', $today)->count();
        $totalPaymentsToday = Payment::whereDate('created_at', $today)->sum('amount');
        $lowStockItems = ServiceSpecification::where('stock', '<=', 5)->count();

        // Pass stats to the view
        return view('admin.dashboard', compact('totalBookingsToday', 'totalPaymentsToday', 'lowStockItems'));
    }
}
