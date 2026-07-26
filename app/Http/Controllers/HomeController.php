<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Partner;
use App\Models\Category;
use App\Models\Event;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $partners = Partner::all();
        $categories = Category::all();
        $query = Event::with('category')
                      ->orderBy('date', 'asc');
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        $events = $query->get();
        $event = $events->first(); // Ambil event pertama untuk hero section
        return view('welcome', compact('partners', 'events', 'event', 'categories'));
    }
}

