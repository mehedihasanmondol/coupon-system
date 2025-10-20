<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coupon_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('template_image'); // Template image path
            $table->integer('qr_x_position'); // QR code X position
            $table->integer('qr_y_position'); // QR code Y position
            $table->integer('qr_size')->default(150); // QR code size in pixels
            $table->string('qr_url_pattern'); // URL pattern with {coupon_code} placeholder
            $table->integer('coupon_number_x_position'); // Coupon number X position
            $table->integer('coupon_number_y_position'); // Coupon number Y position
            $table->integer('coupon_number_font_size')->default(24); // Font size for coupon number
            $table->string('coupon_number_font_color')->default('#000000'); // Font color in hex
            $table->string('coupon_number_font_path')->nullable(); // Custom font path
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupon_templates');
    }
};
