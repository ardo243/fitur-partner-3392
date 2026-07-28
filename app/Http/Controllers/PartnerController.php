<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partner;
use Illuminate\Support\Facades\Storage;


class PartnerController extends Controller
{
    public function index(Request $request)
    {
    $search = $request->search;
    $partners = Partner::where('name', 'LIKE', "%$search%")
                    ->paginate(5)
                    ->withQueryString();

    return view('admin.partners.index', compact('partners'))->with('search', $search);
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo_url'] = $request->file('logo')->store('partners', 'public');
        }

        Partner::create($data);

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil ditambahkan.');
    }

    public function show(Partner $partner)
    {
        return redirect()->route('admin.partners.index');
    }

    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($partner->logo_url && !str_starts_with($partner->logo_url, 'http')) {
                Storage::disk('public')->delete($partner->logo_url);
            }
            $data['logo_url'] = $request->file('logo')->store('partners', 'public');
        }

        $partner->update($data);

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil diperbarui.');
    }

    public function destroy(Partner $partner)
    {
        if ($partner->logo_url && !str_starts_with($partner->logo_url, 'http')) {
            Storage::disk('public')->delete($partner->logo_url);
        }
        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil dihapus.');
    }
}