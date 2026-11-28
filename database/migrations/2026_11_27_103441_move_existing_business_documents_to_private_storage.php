<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('documents')) {
            return;
        }

        $publicDisk = Storage::disk('public');
        $privateDisk = Storage::disk('local');

        DB::table('documents')
            ->select(['id', 'stored_path'])
            ->orderBy('id')
            ->chunkById(100, function ($documents) use ($publicDisk, $privateDisk): void {
                foreach ($documents as $document) {
                    $path = $document->stored_path;

                    if (! is_string($path) || ! $publicDisk->exists($path)) {
                        continue;
                    }

                    $stream = $publicDisk->readStream($path);

                    if (! is_resource($stream)) {
                        throw new RuntimeException("Unable to read public document {$document->id}.");
                    }

                    try {
                        if (! $privateDisk->put($path, $stream) || ! $privateDisk->exists($path)) {
                            throw new RuntimeException("Unable to copy document {$document->id} to private storage.");
                        }
                    } finally {
                        fclose($stream);
                    }

                    if (! $publicDisk->delete($path)) {
                        throw new RuntimeException("Unable to remove public document {$document->id}.");
                    }
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        throw new RuntimeException('Private documents cannot be safely moved back to public storage.');
    }
};
