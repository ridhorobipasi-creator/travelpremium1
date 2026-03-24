<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('province_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->foreignId('city_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('price', 15, 2);
            $table->string('duration');
            $table->json('includes')->nullable();
            $table->json('excludes')->nullable();
            $table->json('images')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->decimal('price_per_day', 15, 2);
            $table->enum('status', ['available', 'booked'])->default('available');
            $table->string('image')->nullable();
            $table->integer('seats')->nullable();
            $table->string('fuel')->nullable();
            $table->string('transmission')->nullable();
            $table->timestamps();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['package', 'car', 'custom']);
            $table->unsignedBigInteger('item_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->decimal('total_price', 15, 2);
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email');
            $table->timestamps();
        });

        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->longText('content');
            $table->string('category');
            $table->json('tags')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('cars');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('provinces');
    }
};
