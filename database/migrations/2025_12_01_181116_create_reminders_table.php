<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->foreignIdFor(User::class)->constrained();
            $table->string('title');
            $table->datetime('scheduled_at');
            $table->datetime('notify_at');
            $table->string('entity')->nullable();
            $table->string('entity_id')->nullable();
            $table->datetime('notified_at')->nullable();
            $table->boolean('is_notified')->default(false);
            $table->integer('current_step')->default(0)->comment('The current step of the reminder. 0 - Pending 1 - In progress - 2 - Done');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
