<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('message_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('recipient_id');
            $table->enum('status', ['pending', 'accepted', 'declined'])->default('pending');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
            
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recipient_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->unique(['sender_id', 'recipient_id']);
        });
        
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->enum('message_type', ['regular', 'request'])->default('regular');
            $table->unsignedBigInteger('message_request_id')->nullable();
            $table->foreign('message_request_id')->references('id')->on('message_requests')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropForeign(['message_request_id']);
            $table->dropColumn(['message_type', 'message_request_id']);
        });
        
        Schema::dropIfExists('message_requests');
    }
};