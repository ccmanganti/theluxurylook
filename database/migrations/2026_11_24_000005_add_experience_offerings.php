<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up():void {
        Schema::create('experience_offerings', function(Blueprint $t){
            $t->id();$t->string('kind',30)->index();$t->string('slug',110);$t->unique(['kind','slug']);
            $t->string('title',250);$t->string('eyebrow',160)->nullable();$t->string('audience',255)->nullable();
            $t->text('summary')->nullable();$t->longText('body')->nullable();$t->text('cover_image')->nullable();
            $t->string('cover_upload')->nullable();$t->string('hero_video')->nullable();$t->string('video_poster')->nullable();
            $t->string('cta',160)->nullable();$t->json('highlights')->nullable();$t->json('gallery')->nullable();
            $t->boolean('featured')->default(false);$t->boolean('is_published')->default(true);
            $t->boolean('content_verified')->default(false);$t->unsignedInteger('sort_order')->default(0);
            $t->string('meta_title')->nullable();$t->text('meta_description')->nullable();$t->timestamps();
        });
        Schema::table('pages',function(Blueprint $t){
            $t->json('sections')->nullable();$t->string('layout_variant',60)->nullable();
            $t->string('hero_video')->nullable();$t->string('hero_video_poster')->nullable();
            $t->string('cta_text',160)->nullable();$t->string('cta_url',255)->nullable();
        });
    }
    public function down():void {
        Schema::table('pages',fn(Blueprint $t)=>$t->dropColumn(['sections','layout_variant','hero_video','hero_video_poster','cta_text','cta_url']));
        Schema::dropIfExists('experience_offerings');
    }
};
