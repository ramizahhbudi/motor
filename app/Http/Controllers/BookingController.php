<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\Service;
use App\Models\ServiceSpecification;
use App\Models\KepemilikanMotor;
use App\Models\TimeSlot;
use App\Models\BookingService;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Menampilkan halaman booking layanan
     */
    public function index()
    {
        // Ambil semua motor milik user
        $userMotors = KepemilikanMotor::where('user_id', Auth::id())
            ->with('jenisMotor')
            ->get();

        // Ambil semua layanan
        $services = Service::with('specifications')->get();

        // Ambil slot waktu yang tersedia (hanya hari ini)
        $availableSlots = TimeSlot::where('date', Carbon::today()->toDateString())
            ->where('is_available', true)
            ->with('mechanic')
            ->get();

        return view('user.services', compact('services', 'userMotors', 'availableSlots'));
    }

    /**
     * Menyimpan data booking
     */
    public function store(Request $request)
    {
        $request->validate([
            'motor_id' => 'required|exists:kepemilikan_motors,id',
            'service_id' => 'required|exists:services,id',
            'service_specification_id' => 'required|exists:service_specifications,id',
            'time_slot_id' => 'required|exists:time_slots,id',
            'notes' => 'nullable|string',
        ]);

        $timeSlot = TimeSlot::find($request->time_slot_id);
        $today = Carbon::today();

        // Get the sequence number for today (count existing bookings for the day)
        $sequence = Booking::whereDate('created_at', $today)->count() + 1;
        $sequence = str_pad($sequence, 4, '0', STR_PAD_LEFT);

        // Generate the booking ID
        $bookingId = $today->format('dmY') . $sequence;
        $mechanicId = $timeSlot->mechanic_id;

        // Pastikan slot masih tersedia
        if (!$timeSlot->is_available) {
            return redirect()->back()->withErrors(['time_slot' => 'Slot waktu yang dipilih tidak tersedia.']);
        }

        // Tandai slot sebagai tidak tersedia
        $timeSlot->update(['is_available' => false]);

        // Simpan booking
        $booking = Booking::create([
            'user_id' => Auth::id(),
            'mechanic_id' => $mechanicId,
            'motor_id' => $request->motor_id,
            'time_slot_id' => $timeSlot->id,
            'status' => 'booked',
            'booking_id' => $bookingId,
        ]);

        // Simpan spesifikasi layanan ke booking_specifications
        BookingService::create([
            'booking_id' => $booking->id,
            'service_specification_id' => $request->service_specification_id,
            'note' => $request->notes,
        ]);

        return redirect()->route('user.bookingconfirmation', ['id' => $booking->id])
            ->with('success', 'Booking berhasil dibuat!');
    }



    /**
     * Mendapatkan spesifikasi layanan (AJAX)
     */
    public function getSpecifications($serviceId)
    {
        $specifications = ServiceSpecification::where('service_id', $serviceId)->get();
        return response()->json($specifications);
    }

    public function confirmation($id)
    {
        // Ambil booking beserta relasi
        $booking = Booking::with([
            'timeSlot',
            'motor.jenisMotor',
            'services.serviceSpecification'
        ])->findOrFail($id);

        // Pastikan user adalah pemilik booking
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan untuk mengakses halaman ini.');
        }

        // Arahkan data ke view
        return view('user.bookingconfirmation', compact('booking'));
    }

    public function getUserBookings()
    {
        // Ambil tanggal hari ini
        $today = Carbon::today();

        // Ambil booking user untuk hari ini
        $bookings = Booking::with(['motor.jenisMotor', 'timeSlot.mechanic', 'services.serviceSpecification'])
            ->where('user_id', Auth::id()) // Hanya booking milik user yang login
            ->whereDate('created_at', $today) // Filter berdasarkan tanggal hari ini
            ->orderBy('created_at', 'desc') // Urutkan berdasarkan waktu terbaru
            ->get();

        // Return data sebagai response JSON (untuk digunakan oleh frontend)
        return response()->json($bookings);
    }
}
