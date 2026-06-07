<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();$table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->dateTime('clock_in_time')->nullable();
            $table->dateTime('clock_out_time')->nullable();
            $table->integer('break_minutes')->default(0);
            $table->enum('status', ['off_work', 'working', 'on_break', 'finished'])->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->unique(['user_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
