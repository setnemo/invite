<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invite_auto_registrations', function (Blueprint $table) {
            $table->bigInteger('invite_id')->primary();
            $table->string('email')->default('');
            $table->string('username')->default('');
            $table->string('password')->default('');
            $table->boolean('done')->default(false);
            $table->boolean('successful')->default(false);
            $table->string('response')->default('');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invite_auto_registrations');
    }
};
