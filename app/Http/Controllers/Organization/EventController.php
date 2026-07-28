<?php
namespace App\Http\Controllers\Organization;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;


class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    $events = Event::with('category')
        ->where('organization_id', session('organization_id'))
        ->latest()
        ->paginate(10);

    return view('organization.events.index', compact('events'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = \App\Models\Category::all();
        return view('organization.events.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
     // Menerapkan validasi data request dari pengguna
     $data = $request->validate([
        'category_id' => 'required|exists:categories,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'date' => 'required|date',
        'location' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|numeric|min:1',
        'poster' => 'nullable|image|max:2048' // Maksimal 2MB
        ]);

    if ($request->hasFile('poster')) {
        // Simpan ke direktori storage/app/public/posters
        $data['poster_path'] = $request->file('poster')->store('posters', 'public');
    }

     $data['organization_id'] = session('organization_id');
     // Menyimpan data yang telah divalidasi ke dalam tabel menggunakan Model
     \App\Models\Event::create($data);

     return redirect()->route('organization.events.index')->with('success', 'Data Event berhasil ditambahkan.');
}

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
   public function edit(Event $event)
{
    if ($event->organization_id != session('organization_id')) {
        abort(403);
    }

    $categories = \App\Models\Category::all();

    return view('organization.events.edit', compact('event', 'categories'));
}

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, Event $event)
{
    // Cek apakah event ini milik organization yang sedang login
    if ($event->organization_id != session('organization_id')) {
        abort(403);
    }

    $data = $request->validate([
        'category_id' => 'required|exists:categories,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'date' => 'required|date',
        'location' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|numeric|min:1',
        'poster' => 'nullable|image|max:2048'
    ]);

    if ($request->hasFile('poster')) {

        if ($event->poster_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($event->poster_path);
        }

        $data['poster_path'] = $request->file('poster')->store('posters', 'public');
    }

    $event->update($data);

    return redirect()->route('organization.events.index')
        ->with('success', 'Event berhasil diperbarui.');
}


    /**
     * Remove the specified resource from storage.
     */
 public function destroy(Event $event)
{
    if ($event->organization_id != session('organization_id')) {
    abort(403);
}
    // 1. Periksa apakah event memiliki file poster dan apakah file tersebut ada di folder storage
    if ($event->poster_path && Storage::disk('public')->exists($event->poster_path)) {
        // 2. Hapus file dari folder storage/app/public/
        Storage::disk('public')->delete($event->poster_path);
    }

    // 3. Hapus data event dari basis data
    $event->delete();

    // 4. Redirect kembali dengan pesan sukses
    return redirect()->route('organization.events.index')
        ->with('success', 'Data event dan file poster berhasil dihapus secara permanen.');
}
}
