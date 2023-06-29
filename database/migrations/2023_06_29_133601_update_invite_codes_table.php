<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invite_codes', function (Blueprint $table) {
            $table->string('remover_handle')->after('recipient_handle')->nullable();
            $table->string('remover_email')->after('recipient_email')->nullable();
            $table->string('remover_did')->after('recipient_did')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('invite_codes', function (Blueprint $table) {
            //
        });
    }
};
