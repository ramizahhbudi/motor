<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminCashierController extends Controller
{
    /**
     * Display the cashier page within the admin panel.
     */
    public function index()
    {
        return view('admin.cashier');
    }

    /**
     * Process a booking by its code to display services and amount due.
     */
    public function process(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|string',
        ]);

        $booking = Booking::with(['services.serviceSpecification', 'user'])->where('booking_id', $request->booking_id)->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking tidak ditemukan.');
        }

        if ($booking->status === 'paid') {
            return redirect()->back()->with('info', 'Booking ini sudah dibayar.');
        }

        $totalAmount = $booking->services->sum(function ($service) {
            return $service->serviceSpecification->price;
        });

        return view('admin.payment', compact('booking', 'totalAmount'));
    }

    /**
     * Finalize payment for a booking.
     */
    public function finalizePayment(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $booking = Booking::with(['services.serviceSpecification', 'user'])->findOrFail($id);

            if ($booking->status === 'paid') {
                return redirect()->route('admin.cashier.index')->with('info', 'Booking ini sudah dibayar.');
            }

            // Deduct stock for each service item
            foreach ($booking->services as $service) {
                $spec = $service->serviceSpecification;
                if ($spec->stock < 1) {
                    return redirect()->route('admin.cashier.index')->with('error', "Stok tidak cukup untuk layanan {$spec->name}.");
                }

                $spec->decrement('stock');
            }

            // Update booking status
            $booking->update(['status' => 'paid']);

            // Record payment
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $request->input('amount'),
                'payment_method' => $request->input('payment_method'),
                'status' => 'paid',
            ]);

            DB::commit();

            // Redirect to receipt page
            return redirect()->route('admin.cashier.receipt', ['id' => $payment->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.cashier')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function receipt($id)
    {
        $payment = Payment::with(['booking.services.serviceSpecification', 'booking.user'])->findOrFail($id);

        return view('admin.receipt', compact('payment'));
    }
}
