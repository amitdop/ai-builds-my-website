<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('businesses')) {
            return;
        }

        DB::table('businesses')
            ->whereNotNull('published_content')
            ->select([
                'id',
                'name',
                'city',
                'google_business_url',
                'facebook_url',
                'instagram_url',
                'website_url',
                'published_content',
            ])
            ->orderBy('id')
            ->chunkById(100, function ($businesses): void {
                foreach ($businesses as $business) {
                    $content = json_decode($business->published_content, true, flags: JSON_THROW_ON_ERROR);

                    if (! is_array($content)) {
                        throw new RuntimeException("Published content for business {$business->id} is invalid.");
                    }

                    $content['business_name'] ??= $business->name;
                    $content['city'] ??= $business->city;
                    $content['links'] ??= [
                        'Google Business' => $business->google_business_url,
                        'Facebook' => $business->facebook_url,
                        'Instagram' => $business->instagram_url,
                        'Website' => $business->website_url,
                    ];

                    DB::table('businesses')
                        ->where('id', $business->id)
                        ->update(['published_content' => json_encode($content, JSON_THROW_ON_ERROR)]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('businesses')) {
            return;
        }

        DB::table('businesses')
            ->whereNotNull('published_content')
            ->select(['id', 'published_content'])
            ->orderBy('id')
            ->chunkById(100, function ($businesses): void {
                foreach ($businesses as $business) {
                    $content = json_decode($business->published_content, true, flags: JSON_THROW_ON_ERROR);

                    if (! is_array($content)) {
                        continue;
                    }

                    unset($content['business_name'], $content['city'], $content['links']);

                    DB::table('businesses')
                        ->where('id', $business->id)
                        ->update(['published_content' => json_encode($content, JSON_THROW_ON_ERROR)]);
                }
            });
    }
};
