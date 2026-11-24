<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up():void {
        Schema::create('studio_videos',function(Blueprint $t){
            $t->id();$t->string('title');$t->text('youtube_url');$t->text('poster_url')->nullable();
            $t->string('placement',50)->default('services');$t->text('caption')->nullable();
            $t->unsignedInteger('sort_order')->default(0);$t->boolean('is_published')->default(false);
            $t->boolean('content_verified')->default(false);$t->timestamps();
        });
    }
    public function down():void {Schema::dropIfExists('studio_videos');}
};
