<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('zalo_settings', function (Blueprint $table) {
            $table->id();
            $table->string('zalo_contact')->unique()->comment('Zalo OA ID hoặc số điện thoại');
            $table->string('zalo_type')->default('phone')->comment('Loại: oa hoặc phone');
            $table->string('messenger_contact')->nullable();
            $table->string('messenger_type')->default('facebook');
            $table->string('messenger_icon')->default('fab fa-facebook-messenger');
            $table->string('call_contact')->nullable();
            $table->string('call_type')->default('phone');
            $table->string('call_icon')->default('fas fa-phone');
            $table->string('zalo_icon')->default('fas fa-comment');
            $table->timestamps();
        });

        // Insert default value
        DB::table('zalo_settings')->insert([
            'zalo_contact' => '0123456789', // Default số điện thoại
            'zalo_type' => 'phone',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('zalo_settings');
    }
};
