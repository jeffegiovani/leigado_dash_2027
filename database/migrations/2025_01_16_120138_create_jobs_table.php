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
        Schema::create('job_vacancies', function (Blueprint $table) {
            $table->engine = 'MyISAM';

            $table->id();

            $table->bigInteger('author_id')->unsigned();
            $table->string('visibility', 20)->default(ResourceVisibilityEnum::Public)->nullable();
            $table->string('title', 120);
            $table->string('slug', 125);
            $table->string('location', 40);
            $table->text('content');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_vacancies');
    }
};
