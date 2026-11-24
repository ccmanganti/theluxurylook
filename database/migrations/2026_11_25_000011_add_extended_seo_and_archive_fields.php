<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('posts', function(Blueprint $t) {
            $t->string('category',80)->nullable();
            $t->text('source_url')->nullable();
            $t->string('source_attribution',180)->nullable();
            $t->string('seo_locality',100)->nullable();
            $t->text('seo_share_image')->nullable();
        });
        foreach(['pages','services','portfolio_categories','projects','experience_offerings'] as $table){
            Schema::table($table,function(Blueprint $t){
                $t->string('seo_locality',100)->nullable();
                $t->text('seo_share_image')->nullable();
            });
        }
    }
    public function down(): void {
        Schema::table('posts',fn(Blueprint $t)=>$t->dropColumn(['category','source_url','source_attribution','seo_locality','seo_share_image']));
        foreach(['pages','services','portfolio_categories','projects','experience_offerings'] as $table){
            Schema::table($table,fn(Blueprint $t)=>$t->dropColumn(['seo_locality','seo_share_image']));
        }
    }
};
