<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->string('scope')->default('tour')->after('status')->index();
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('scope')->default('tour')->after('category')->index();
        });
        
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('scope')->default('tour')->after('status')->index();
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('scope');
        });

        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn('scope');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('scope');
        });
    }
};
