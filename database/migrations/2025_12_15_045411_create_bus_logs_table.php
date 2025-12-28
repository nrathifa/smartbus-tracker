<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('bus_logs', function (Blueprint $table) {
            $table->id();
            $table->string('card_uid');
            $table->string('matric_no')->nullable();
            $table->string('student_name')->nullable(); // <--- ADD THIS LINE
            $table->foreignId('bus_id')->nullable();
            $table->enum('action', ['BOARDING', 'EXITING']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_logs');
    }
};
