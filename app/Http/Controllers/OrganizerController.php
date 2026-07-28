<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Organization;

class OrganizerController extends Controller
{
    public function show(Partner $partner)
    {
        // Ambil semua event milik partner ini
        $events = $partner->events()->with('reviews.user')->get();

        // Cari semua review dari semua event
        $reviews = collect();
        foreach ($events as $event) {
            foreach ($event->reviews as $review) {
                $reviews->push($review);
            }
        }

        // Hitung rata-rata rating
        $averageRating = $reviews->count() > 0 ? round($reviews->avg('rating'), 1) : 0;

        return view('organizers.show', compact('partner', 'events', 'reviews', 'averageRating'));
    }

    public function showOrganization(Organization $organization)
    {
        // Ambil semua event milik organisasi ini
        $events = $organization->events()->with(['category', 'reviews.user'])->get();

        // Cari semua review dari semua event
        $reviews = collect();
        foreach ($events as $event) {
            foreach ($event->reviews as $review) {
                $review->event_title = $event->title;
                $reviews->push($review);
            }
        }

        // Hitung rata-rata rating dan total ulasan
        $reviewCount = $reviews->count();
        $averageRating = $reviewCount > 0 ? round($reviews->avg('rating'), 1) : 0.0;

        // Hitung distribusi bintang
        $starDistribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        foreach ($reviews as $review) {
            $rating = (int) $review->rating;
            if (isset($starDistribution[$rating])) {
                $starDistribution[$rating]++;
            }
        }
        $starPercentages = [];
        foreach ($starDistribution as $star => $count) {
            $starPercentages[$star] = $reviewCount > 0 ? round(($count / $reviewCount) * 100) : 0;
        }

        // Hitung total tiket terjual
        $ticketsSold = \App\Models\Transaction::whereHas('event', function ($query) use ($organization) {
            $query->where('organization_id', $organization->id);
        })->whereIn('status', ['settlement', 'success'])->count();

        // Bagi event menjadi aktif/mendatang dan selesai
        $activeEvents = $events->filter(function ($event) {
            return $event->date->gte(now());
        });
        $completedEvents = $events->filter(function ($event) {
            return $event->date->lt(now());
        });

        return view('organization.profile', compact(
            'organization', 
            'events', 
            'activeEvents', 
            'completedEvents', 
            'reviews', 
            'averageRating', 
            'reviewCount', 
            'starDistribution', 
            'starPercentages', 
            'ticketsSold'
        ));
    }
}
