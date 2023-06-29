<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invites', function (Blueprint $table) {
            $table->id();
            $table->string('link');
            $table->integer('train_number');
            $table->string('remover_handle')->after('recipient_handle')->nullable();
            $table->string('remover_email')->after('recipient_email')->nullable();
            $table->string('remover_did')->after('recipient_did')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invites');
    }
};
