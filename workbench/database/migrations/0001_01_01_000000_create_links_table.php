<?php

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
        Schema::connection(config('short-url.connection'))->create('links', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('token')->unique();
            $table->string('destination');
            $table->text('headers')->nullable();
            $table->text('queries')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('available_at');
            $table->boolean('is_single_use')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(config('short-url.connection'))->dropIfExists('links');
    }
};
