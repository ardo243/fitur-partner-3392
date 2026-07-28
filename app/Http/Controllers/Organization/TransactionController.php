<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        $organizationId = session('organization_id');

        // Mengambil transaksi untuk event milik organisasi ini
        $transactions = Transaction::whereHas('event', function ($query) use ($organizationId) {
            $query->where('organization_id', $organizationId);
        })->with('event')->latest()->paginate(20);

        return view('organization.transactions.index', compact('transactions'));
    }
}
