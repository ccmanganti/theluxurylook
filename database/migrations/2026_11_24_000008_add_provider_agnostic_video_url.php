<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('studio_videos', function (Blueprint $table) {
            $table->text('video_url')->nullable()->after('youtube_url');
            $table->text('youtube_url')->nullable()->change();
        });
        // Preserve the original studio video and its legacy URL.
        DB::table('studio_videos')->whereNull('video_url')->whereNotNull('youtube_url')
            ->update(['video_url' => DB::raw('youtube_url')]);
    }
    public function down(): void {
        DB::table('studio_videos')->whereNull('youtube_url')->whereNotNull('video_url')
            ->update(['youtube_url' => DB::raw('video_url')]);
        Schema::table('studio_videos', function (Blueprint $table) {
            $table->dropColumn('video_url');
            $table->text('youtube_url')->nullable(false)->change();
        });
    }
};
