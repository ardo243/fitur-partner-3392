<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Organization;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $organizations = Organization::where('name', 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%")
                        ->paginate(5)
                        ->withQueryString();

        return view('admin.organizations.index', compact('organizations'))->with('search', $search);
    }

    public function create()
    {
        return view('admin.organizations.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'pic_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:organizations,email',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:20',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = $path;
        }

        $data['password'] = Hash::make($data['password']);

        Organization::create($data);

        return redirect()->route('admin.organizations.index')->with('success', 'Organisasi berhasil ditambahkan.');
    }

    public function show(Organization $organization)
    {
        return redirect()->route('admin.organizations.index');
    }

    public function edit(Organization $organization)
    {
        return view('admin.organizations.edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'pic_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:organizations,email,' . $organization->id,
            'password' => 'nullable|string|min:6',
            'phone' => 'required|string|max:20',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($organization->logo && Storage::disk('public')->exists($organization->logo)) {
                Storage::disk('public')->delete($organization->logo);
            }
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = $path;
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $organization->update($data);

        return redirect()->route('admin.organizations.index')->with('success', 'Organisasi berhasil diperbarui.');
    }

    public function destroy(Organization $organization)
    {
        // Hapus file logo jika ada
        if ($organization->logo && Storage::disk('public')->exists($organization->logo)) {
            Storage::disk('public')->delete($organization->logo);
        }

        $organization->delete();

        return redirect()->route('admin.organizations.index')->with('success', 'Organisasi berhasil dihapus.');
    }
}
