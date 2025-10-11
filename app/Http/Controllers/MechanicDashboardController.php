<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MechanicDashboardController extends Controller
{
    /**
     * Display the mechanic's dashboard with their service schedule.
     */
    public function index()
    {
        // Get today's date
        $today = Carbon::today();

        // Fetch bookings assigned to the logged-in mechanic
        $schedules = Booking::with(['motor.jenisMotor', 'timeSlot', 'user', 'services.serviceSpecification'])
            ->whereHas('timeSlot', function ($query) use ($today) {
                $query->where('mechanic_id', Auth::id())
                    ->whereDate('date', $today);
            })
            ->join('time_slots', 'bookings.time_slot_id', '=', 'time_slots.id')
            ->orderBy('time_slots.start_time', 'asc')
            ->select('bookings.*') // Ensure only bookings fields are selected
            ->get();

        return view('mechanic.dashboard', compact('schedules'));
    }
}
