<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('location_tag')->nullable();
            $table->string('pre_order_info')->nullable();
            $table->json('pricing_details')->nullable();
            $table->decimal('price_min', 15, 2)->nullable();
            $table->decimal('price_max', 15, 2)->nullable();
            $table->decimal('child_price', 15, 2)->nullable();
            $table->string('price_display')->nullable();
            $table->text('short_description')->nullable();
            $table->json('itinerary')->nullable();
            $table->longText('itinerary_text')->nullable();
            $table->decimal('drone_price', 15, 2)->nullable();
            $table->string('drone_location')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('translations')->nullable();
        });

        Schema::table('cars', function (Blueprint $table) {
            $table->unsignedBigInteger('vehicle_id')->nullable();
            $table->string('category')->nullable();
            $table->integer('capacity')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price_with_driver', 15, 2)->nullable();
            $table->json('pricing_details')->nullable();
            $table->json('features')->nullable();
            $table->json('includes')->nullable();
            $table->text('terms')->nullable();
            $table->json('featured_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('translations')->nullable();
        });
    }

    public function down(): void
    {
        // Add dropColumn array
    }
};
