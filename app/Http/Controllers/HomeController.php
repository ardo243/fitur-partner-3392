<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Partner;
use App\Models\Category;
use App\Models\Event;
use App\Models\Organization;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $partners = Partner::all();
        $organizations = Organization::where('status', 'active')->latest()->get();
        $categories = Category::all();
        $query = Event::with('category')
                      ->where('date', '>=', now())
                      ->orderBy('date', 'asc');
        if ($request->has('category') && $request->category != '') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        $events = $query->get();
        $event = $events->first(); // Ambil event pertama untuk hero section
        return view('welcome', compact('partners', 'events', 'event', 'categories', 'organizations'));
    }
}

