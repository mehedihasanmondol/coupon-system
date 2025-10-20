<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('prize_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('prizes'); // [{position: 1, prize_name: 'ব্যাগ'}, ...]
            $table->boolean('is_announced')->default(false);
            $table->timestamp('announced_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prize_templates');
    }
};
