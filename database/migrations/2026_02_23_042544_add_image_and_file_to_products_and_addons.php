<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('image_path')->after('name')->nullable();
            $table->string('file_path')->after('download_url')->nullable();
        });

        Schema::table('add_ons', function (Blueprint $table) {
            $table->string('image_path')->after('price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['image_path', 'file_path']);
        });

        Schema::table('add_ons', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
};
