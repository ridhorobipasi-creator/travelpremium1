<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('package_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('customer_name');
            $table->integer('rating')->default(5);
            $table->text('comment');
            $table->boolean('is_approved')->default(false);
            $table->string('scope')->default('tour'); // tour / outbound
            $table->timestamps();
            
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
