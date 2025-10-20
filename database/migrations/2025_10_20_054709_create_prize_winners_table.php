<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('prize_winners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->onDelete('cascade');
            $table->foreignId('prize_template_id')->constrained()->onDelete('cascade');
            $table->integer('position');
            $table->string('prize_name');
            $table->string('winner_name')->nullable();
            $table->string('winner_photo')->nullable();
            $table->string('winner_address')->nullable();
            $table->integer('winner_age')->nullable();
            $table->string('winner_mobile')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prize_winners');
    }
};
