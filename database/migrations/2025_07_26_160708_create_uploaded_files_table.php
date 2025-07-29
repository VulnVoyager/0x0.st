<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uploaded_files', function (Blueprint $table) {
            $table->id();
            $table->string('file_hash', 32)->unique();
            $table->string('delete_token', 64);
            $table->string('original_name');
            $table->string('mime_type');
            $table->unsignedBigInteger('file_size');
            $table->string('file_path');
            $table->timestamp('expires_at');
            $table->timestamps();
            
            $table->index(['expires_at']);
            $table->index(['file_hash']);
            $table->index(['delete_token']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uploaded_files');
    }
};