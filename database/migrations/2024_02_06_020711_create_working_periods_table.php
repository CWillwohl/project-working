<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('working_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('punch_in_id')->references('id')->on('punch_clocks')->onDelete('cascade');
            $table->foreignId('punch_out_id')->nullable()->references('id')->on('punch_clocks')->onDelete('cascade');
            $table->timestamp('punch_in_time');
            $table->timestamp('punch_out_time')->nullable();
            $table->float('value_to_receive')->default('0.00');
            $table->string('time_worked')->default('00:00');
            $table->boolean('received')->default(false)->index();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('working_periods');
    }
};
