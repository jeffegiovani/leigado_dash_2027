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
        Schema::create('site_contacts', function (Blueprint $table) {
            $table->engine = 'MyISAM';

            $table->id();

            $table->string('name', 80)->nullable();
            $table->string('email', 80);
            $table->string('phone', 20)->nullable();
            $table->string('channel', 20);
            $table->json('content')->nullable();
            $table->char('locale', 5)->nullable()->default(app()->getLocale());

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_contacts');
    }
};
