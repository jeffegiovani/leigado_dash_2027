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
        Schema::create('coupons', function (Blueprint $table) {
            $table->engine = 'MyISAM';

            $table->id();

            $table->bigInteger('author_id')->unsigned();
            $table->json('segments');
            $table->string('visibility', 20)->default(ResourceVisibilityEnum::Public)->nullable();
            $table->string('avatar');
            $table->string('cover')->nullable();
            $table->string('partner', 80);
            $table->string('title', 120);
            $table->string('slug', 125);
            $table->string('offer_headline', 20);
            $table->string('cta');
            $table->text('content');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
