<?php

namespace App\Http\Controllers;

use App\Models\ServiceSpecification;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a list of all service specifications (inventory).
     */
    public function index(Request $request)
    {
        // Fetch all inventory items with optional search filter
        $search = $request->get('search', null);
        $inventory = ServiceSpecification::with('service')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%$search%")
                      ->orWhereHas('service', function ($q) use ($search) {
                          $q->where('name', 'like', "%$search%");
                      });
            })
            ->orderBy('name', 'asc')
            ->paginate(10); // Paginate results

        return view('admin.inventory', compact('inventory', 'search'));
    }

    /**
     * Update the stock of a specific item.
     */
    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $item = ServiceSpecification::findOrFail($id);
        $item->update(['stock' => $request->input('stock')]);

        return redirect()->route('admin.inventory')->with('success', 'Stok berhasil diperbarui!');
    }
}
