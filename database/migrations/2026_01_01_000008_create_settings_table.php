<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->nullable()->constrained('stores')->onDelete('cascade');
            $table->string('site_name');
            $table->text('address')->nullable();
            $table->longText('description')->nullable();
            $table->longText('keywords')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('email_support')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('instegram')->nullable();
            $table->string('youtube')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();

            // auth welcome content
            $table->text('auth_welcome_title')->nullable();
            $table->text('auth_welcome_desc')->nullable();
            $table->text('auth_welcome_badge')->nullable();
            $table->text('auth_welcome_footer')->nullable();
            $table->foreignId('currency_id')->nullable()->constrained('currencies')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
