<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskReminderLogsTable extends Migration
{
    public function up()
    {
        Schema::create('task_reminder_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // academic staff
            $table->timestamp('reminder_sent_at');
            $table->string('task_status');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('task_reminder_logs');
    }
}
