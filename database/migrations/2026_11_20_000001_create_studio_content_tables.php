<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pages', function (Blueprint $t) {
            $t->id(); $t->string('path')->unique(); $t->string('title'); $t->string('eyebrow')->nullable();
            $t->text('intro')->nullable();$t->longText('body')->nullable();$t->text('hero_image')->nullable();
            $t->string('meta_title')->nullable();$t->text('meta_description')->nullable();
            $t->boolean('is_published')->default(false);$t->boolean('content_verified')->default(false);$t->timestamps();
        });
        Schema::create('services', function(Blueprint $t) {
            $t->id();$t->string('slug')->unique();$t->string('title');$t->text('summary')->nullable();
            $t->longText('body')->nullable();$t->text('cover_image')->nullable();$t->string('cover_upload')->nullable();
            $t->string('meta_title')->nullable();$t->text('meta_description')->nullable();
            $t->unsignedInteger('sort_order')->default(0);$t->boolean('is_published')->default(false);$t->boolean('content_verified')->default(false);$t->timestamps();
        });
        Schema::create('portfolio_categories', function(Blueprint $t) {
            $t->id();$t->string('slug')->unique();$t->string('title');$t->string('tagline')->nullable();
            $t->text('description')->nullable();$t->text('hero_image')->nullable();
            $t->string('meta_title')->nullable();$t->text('meta_description')->nullable();
            $t->unsignedInteger('sort_order')->default(0);$t->boolean('is_published')->default(false);$t->boolean('content_verified')->default(false);$t->timestamps();
        });
        Schema::create('projects', function(Blueprint $t) {
            $t->id();$t->foreignId('portfolio_category_id')->nullable()->constrained()->nullOnDelete();
            $t->string('slug')->unique();$t->string('title');$t->string('location')->nullable();
            $t->text('summary')->nullable();$t->longText('story')->nullable();
            $t->text('cover_image')->nullable();$t->string('cover_upload')->nullable();
            $t->json('gallery')->nullable();$t->json('gallery_uploads')->nullable();
            $t->string('meta_title')->nullable();$t->text('meta_description')->nullable();
            $t->boolean('featured')->default(false);$t->boolean('is_published')->default(false);
            $t->boolean('content_verified')->default(false);$t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
        });
        Schema::create('posts', function(Blueprint $t) {
            $t->id();$t->string('slug')->unique();$t->string('title');$t->text('excerpt')->nullable();
            $t->longText('body')->nullable();$t->text('cover_image')->nullable();$t->string('cover_upload')->nullable();
            $t->string('meta_title')->nullable();$t->text('meta_description')->nullable();
            $t->boolean('is_published')->default(false);$t->boolean('content_verified')->default(false);
            $t->timestamp('published_at')->nullable();$t->timestamps();
        });
        Schema::create('legacy_urls', function(Blueprint $t) {
            $t->id();$t->string('path')->unique();$t->string('source_url');
            $t->string('content_type')->default('page');
            $t->string('status')->default('needs_content'); // needs_content, ready, redirect, retired
            $t->string('redirect_to')->nullable();$t->boolean('included_in_original_sitemap')->default(true);
            $t->text('migration_notes')->nullable();$t->timestamps();
        });
        Schema::create('inquiries', function(Blueprint $t) {
            $t->id();$t->string('name');$t->string('email');$t->string('phone')->nullable();
            $t->string('service')->nullable();$t->longText('message');$t->string('status')->default('new');
            $t->timestamps();
        });
    }
    public function down(): void {
        foreach(['inquiries','legacy_urls','posts','projects','portfolio_categories','services','pages'] as $table) Schema::dropIfExists($table);
    }
};
