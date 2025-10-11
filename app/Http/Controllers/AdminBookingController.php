<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    /**
     * Display the list of bookings with filtering and sorting functionality.
     */
    public function index(Request $request)
    {
        // Get filter and sorting parameters
        $filterDate = $request->get('filter', 'today'); // Default: today
        $search = $request->get('search', null);        // Search query
        $sortOrder = $request->get('sort', 'desc');     // Sort order (asc/desc)

        // Determine the date range based on the filter
        switch ($filterDate) {
            case 'yesterday':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday();
                break;
            case 'last7days':
                $startDate = Carbon::now()->subDays(7);
                $endDate = Carbon::today();
                break;
            case 'last30days':
                $startDate = Carbon::now()->subDays(30);
                $endDate = Carbon::today();
                break;
            case 'all': // No date filter
                $startDate = null;
                $endDate = null;
                break;
            case 'today':
            default:
                $startDate = Carbon::today();
                $endDate = Carbon::today();
                break;
        }

        // Fetch bookings with filters and sorting
        $bookings = Booking::with(['user', 'motor.jenisMotor', 'timeSlot.mechanic', 'services.serviceSpecification'])
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereDate('created_at', '>=', $startDate)
                      ->whereDate('created_at', '<=', $endDate);
            })
            ->when($search, function ($query) use ($search) {
                $query->where('booking_id', 'like', "%$search%")
                      ->orWhereHas('user', function ($q) use ($search) {
                          $q->where('name', 'like', "%$search%");
                      })
                      ->orWhereHas('motor', function ($q) use ($search) {
                          $q->where('plat', 'like', "%$search%");
                      });
            })
            ->orderBy('created_at', $sortOrder)
            ->get();

        // Pass data to the view
        return view('admin.bookings', compact('bookings', 'filterDate', 'search', 'sortOrder'));
    }
}
