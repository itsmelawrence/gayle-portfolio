<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('email', 190)->index();
            $table->string('company', 160)->nullable();
            $table->string('service', 120);
            $table->text('details');
            // Pipeline status, updated by hand for now: new, contacted, won, lost
            $table->string('status', 20)->default('new')->index();
            // Where the lead came from (first page view of the visit)
            $table->string('utm_source', 100)->nullable();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 150)->nullable();
            $table->string('referrer', 500)->nullable();
            $table->string('landing_page', 500)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            // Set once the notification email has gone out; null means it still needs a reply prompt
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
