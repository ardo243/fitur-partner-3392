<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::latest()->paginate(15);
        return view('admin.vouchers.index', compact('vouchers'));
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'           => 'required|string|max:50|unique:vouchers,code',
            'discount_type'  => 'required|in:percent,fixed',
            'discount_value' => 'required|integer|min:1',
            'quota'          => 'required|integer|min:1',
            'valid_from'     => 'nullable|date',
            'valid_until'    => 'nullable|date|after_or_equal:valid_from',
            'is_active'      => 'boolean',
            'description'    => 'nullable|string|max:500',
        ]);

        $data['code']      = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active', true);

        Voucher::create($data);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher ' . $data['code'] . ' berhasil dibuat!');
    }

    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    public function update(Request $request, Voucher $voucher)
    {
        $data = $request->validate([
            'code'           => 'required|string|max:50|unique:vouchers,code,' . $voucher->id,
            'discount_type'  => 'required|in:percent,fixed',
            'discount_value' => 'required|integer|min:1',
            'quota'          => 'required|integer|min:' . $voucher->used_count,
            'valid_from'     => 'nullable|date',
            'valid_until'    => 'nullable|date|after_or_equal:valid_from',
            'is_active'      => 'boolean',
            'description'    => 'nullable|string|max:500',
        ]);

        $data['code']      = strtoupper($data['code']);
        $data['is_active'] = $request->boolean('is_active');

        $voucher->update($data);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher berhasil diperbarui!');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher berhasil dihapus.');
    }

    /**
     * Toggle status aktif/nonaktif voucher.
     */
    public function toggleStatus(Voucher $voucher)
    {
        $voucher->update(['is_active' => !$voucher->is_active]);
        $status = $voucher->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.vouchers.index')
            ->with('success', "Voucher {$voucher->code} berhasil {$status}.");
    }
}
