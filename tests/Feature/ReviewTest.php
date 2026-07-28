<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Review;
use App\Models\Partner;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    protected $category;
    protected $partner;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed default category & partner
        $this->category = Category::create(['name' => 'Seminar', 'slug' => 'seminar']);
        $this->partner = Partner::create(['name' => 'Amikom Organizers', 'logo_url' => 'https://placehold.co/200x200']);
    }

    public function test_guest_cannot_submit_review()
    {
        $event = Event::create([
            'category_id' => $this->category->id,
            'partner_id' => $this->partner->id,
            'title' => 'Sample Seminar',
            'date' => now()->subDays(2),
            'location' => 'Amikom Cinema',
            'price' => 10000,
            'stock' => 10,
        ]);

        $response = $this->post(route('events.reviews.store', $event->id), [
            'rating' => 5,
            'review' => 'Great event!',
        ]);

        $response->assertRedirect(route('admin.login'));
    }

    public function test_user_cannot_submit_review_without_purchase()
    {
        $user = User::factory()->create();
        $event = Event::create([
            'category_id' => $this->category->id,
            'partner_id' => $this->partner->id,
            'title' => 'Sample Seminar',
            'date' => now()->subDays(2),
            'location' => 'Amikom Cinema',
            'price' => 10000,
            'stock' => 10,
        ]);

        $response = $this->actingAs($user)->post(route('events.reviews.store', $event->id), [
            'rating' => 5,
            'review' => 'Great event!',
        ]);

        $response->assertSessionHasErrors('review');
    }

    public function test_user_cannot_submit_review_before_event_finished_plus_one_day()
    {
        $user = User::factory()->create();
        $event = Event::create([
            'category_id' => $this->category->id,
            'partner_id' => $this->partner->id,
            'title' => 'Sample Seminar',
            'date' => now(), // today, not 1 day ago
            'location' => 'Amikom Cinema',
            'price' => 10000,
            'stock' => 10,
        ]);

        // Purchase ticket
        Transaction::create([
            'event_id' => $event->id,
            'order_id' => 'TRX-101',
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '0812345678',
            'total_price' => 15000,
            'status' => 'success',
        ]);

        $response = $this->actingAs($user)->post(route('events.reviews.store', $event->id), [
            'rating' => 5,
            'review' => 'Great event!',
        ]);

        $response->assertSessionHasErrors('review');
    }

    public function test_user_can_submit_review_after_event_finished_plus_one_day()
    {
        $user = User::factory()->create();
        $event = Event::create([
            'category_id' => $this->category->id,
            'partner_id' => $this->partner->id,
            'title' => 'Sample Seminar',
            'date' => now()->subDays(2),
            'location' => 'Amikom Cinema',
            'price' => 10000,
            'stock' => 10,
        ]);

        // Purchase ticket
        Transaction::create([
            'event_id' => $event->id,
            'order_id' => 'TRX-101',
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '0812345678',
            'total_price' => 15000,
            'status' => 'success',
        ]);

        $response = $this->actingAs($user)->post(route('events.reviews.store', $event->id), [
            'rating' => 5,
            'review' => 'Great event!',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'event_id' => $event->id,
            'rating' => 5,
            'review' => 'Great event!',
        ]);
    }

    public function test_user_cannot_submit_duplicate_review()
    {
        $user = User::factory()->create();
        $event = Event::create([
            'category_id' => $this->category->id,
            'partner_id' => $this->partner->id,
            'title' => 'Sample Seminar',
            'date' => now()->subDays(2),
            'location' => 'Amikom Cinema',
            'price' => 10000,
            'stock' => 10,
        ]);

        // Purchase ticket
        Transaction::create([
            'event_id' => $event->id,
            'order_id' => 'TRX-101',
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '0812345678',
            'total_price' => 15000,
            'status' => 'success',
        ]);

        // First review
        Review::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'rating' => 4,
            'review' => 'Already reviewed once',
        ]);

        $response = $this->actingAs($user)->post(route('events.reviews.store', $event->id), [
            'rating' => 5,
            'review' => 'Try duplicate review',
        ]);

        $response->assertSessionHasErrors('review');
    }

    public function test_google_sso_redirect()
    {
        $response = $this->get(route('auth.google'));
        $response->assertRedirect();
        $this->assertStringContainsString('accounts.google.com', $response->headers->get('Location'));
    }
}
