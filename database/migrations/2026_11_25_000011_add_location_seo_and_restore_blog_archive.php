<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        foreach (['pages', 'services', 'portfolio_categories', 'projects', 'posts'] as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->string('seo_focus_location',120)->nullable();
                $table->text('seo_image_alt')->nullable();
            });
        }
        Schema::table('posts', function (Blueprint $table) {
            $table->string('category',100)->nullable();
            $table->string('author_name',160)->nullable();
            $table->text('source_url')->nullable();
        });
    }
    public function down(): void {
        Schema::table('posts', fn(Blueprint $table) => $table->dropColumn(['category','author_name','source_url']));
        foreach (['pages','services','portfolio_categories','projects','posts'] as $name) {
            Schema::table($name, fn(Blueprint $table) => $table->dropColumn(['seo_focus_location','seo_image_alt']));
        }
    }
};
