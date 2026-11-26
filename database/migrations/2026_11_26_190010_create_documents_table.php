<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            // Which business this file belongs to. Deleting the business deletes its files' rows.
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();

            $table->string('original_name');            // e.g. "price-list.pdf"
            $table->string('stored_path');              // where Laravel saved it on disk
            $table->string('mime_type')->nullable();    // e.g. application/pdf, image/jpeg
            $table->unsignedBigInteger('size')->default(0); // size in bytes
            $table->string('kind')->default('document'); // "document" or "image"

            // Filled in later, when we read the file
            $table->longText('extracted_text')->nullable();
            $table->string('extraction_status')->default('pending'); // pending, done, failed
            $table->text('extraction_error')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
