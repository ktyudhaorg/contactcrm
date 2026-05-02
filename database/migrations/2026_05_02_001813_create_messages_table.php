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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->cascadeOnDelete();
            $table->string('message_id')->nullable();
            $table->foreignId('sender_id')->nullable()->constrained('users');
            $table->enum('sender_type', ['contact', 'agent', 'bot', 'system']);
            $table->enum('channel', ['email', 'whatsapp', 'telegram']);
            $table->enum('content_type', ['text', 'image', 'file', 'document', 'audio', 'video']);
            $table->text('body')->nullable();

            $table->text('attachment')->nullable();
            $table->timestamp('read_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
