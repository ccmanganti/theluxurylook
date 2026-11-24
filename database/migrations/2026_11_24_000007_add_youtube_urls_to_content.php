<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up():void {
        Schema::table('pages',function(Blueprint $t){$t->text('youtube_url')->nullable();});
        Schema::table('experience_offerings',function(Blueprint $t){$t->text('youtube_url')->nullable();});
    }
    public function down():void {
        Schema::table('experience_offerings',fn(Blueprint $t)=>$t->dropColumn('youtube_url'));
        Schema::table('pages',fn(Blueprint $t)=>$t->dropColumn('youtube_url'));
    }
};
