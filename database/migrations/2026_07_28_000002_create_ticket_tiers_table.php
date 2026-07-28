<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_tiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('name');             // Early Bird, Presale 1, Regular, dst.
            $table->integer('price');           // harga tier ini
            $table->integer('quota');           // kuota tiket di tier ini
            $table->integer('sold_count')->default(0); // sudah terjual
            $table->dateTime('sale_start')->nullable(); // mulai dijual
            $table->dateTime('sale_end')->nullable();   // berakhir dijual
            $table->integer('sort_order')->default(0);  // urutan: 1=paling awal, dst.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_tiers');
    }
};
