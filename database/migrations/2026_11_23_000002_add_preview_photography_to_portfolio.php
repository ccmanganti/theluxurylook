<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->text('preview_cover_image')->nullable();
            $table->json('preview_gallery')->nullable();
        });
        Schema::table('portfolio_categories', function (Blueprint $table) {
            $table->text('preview_hero_image')->nullable();
            $table->string('hero_upload')->nullable();
        });
        Schema::table('services', function (Blueprint $table) {
            $table->text('preview_cover_image')->nullable();
        });
    }
    public function down(): void
    {
        Schema::table('services', fn(Blueprint $table) => $table->dropColumn('preview_cover_image'));
        Schema::table('portfolio_categories', fn(Blueprint $table) => $table->dropColumn(['preview_hero_image', 'hero_upload']));
        Schema::table('projects', fn(Blueprint $table) => $table->dropColumn(['preview_cover_image', 'preview_gallery']));
    }
};
