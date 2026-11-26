<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();

            // What the business owner types into the form
            $table->string('name');
            $table->string('city');
            $table->string('google_business_url', 500)->nullable();
            $table->string('facebook_url', 500)->nullable();
            $table->string('instagram_url', 500)->nullable();
            $table->string('website_url', 500)->nullable();

            // Where this business is in the process:
            // draft -> generating -> generated -> published (or failed)
            $table->string('status')->default('draft');

            // The AI-written website content, saved as JSON
            $table->json('generated_content')->nullable();

            // The public address of the published site, e.g. /sites/sharma-jewellers
            $table->string('slug')->nullable()->unique();
            $table->timestamp('published_at')->nullable();

            // If the AI fails, we store a friendly reason here
            $table->text('error_message')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};