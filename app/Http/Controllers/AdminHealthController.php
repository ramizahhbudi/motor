<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlockLedger;
use App\Services\BlockLedgerService;

class AdminHealthController extends Controller
{
    public function healthCheck()
    {
        $audit = \App\Services\BlockLedgerService::validateChain();
        $ledgers = \App\Models\BlockLedger::latest()->paginate(10);
        
        return view('admin.syshealth', compact('audit', 'ledgers'));
    }
}
