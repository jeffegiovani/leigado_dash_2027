<?php

use App\Enums\ResourceVisibilityEnum;
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
        Schema::create('success_cases', function (Blueprint $table) {
            $table->engine = 'MyISAM';

            $table->id();

            $table->json('segments');
            $table->string('visibility', 20)->default(ResourceVisibilityEnum::Public)->nullable();

            $table->string('avatar')->nullable();
            $table->string('name')->nullable();
            $table->string('job_position')->nullable();
            $table->tinyText('testimony')->nullable();

            $table->string('logotype')->nullable();
            $table->string('customer_name', 120)->nullable();
            $table->string('customer_location', 120)->nullable();

            $table->string('cover')->nullable();
            $table->string('title', 120)->nullable();
            $table->string('slug', 125)->nullable();
            $table->text('embed_video')->nullable();
            $table->string('cta')->nullable();
            $table->text('content')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('success_cases');
    }
};
