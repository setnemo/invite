<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invite_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->integer('train_number')->index();
            $table->string('giver_handle')->nullable()->index();
            $table->string('giver_email')->nullable()->index();
            $table->string('giver_did')->nullable()->index();
            $table->string('recipient_handle')->nullable();
            $table->string('recipient_email')->nullable();
            $table->string('recipient_did')->nullable();
            $table->timestamp('booked_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invite_codes');
    }
};
