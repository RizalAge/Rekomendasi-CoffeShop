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
        Schema::table('coffee_shops', function (Blueprint $table) {
            $table->string('nama_pemilik')->nullable()->after('name');
            $table->string('nik_pemilik')->nullable()->after('nama_pemilik');
            $table->text('alamat_cafe')->nullable()->after('nik_pemilik');
            $table->json('kategori')->nullable()->after('alamat_cafe');
            $table->json('fasilitas')->nullable()->after('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coffee_shops', function (Blueprint $table) {
            //
        });
    }
};
