<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::table('posts',function(Blueprint $t){
   $t->string('category',100)->nullable();$t->text('source_url')->nullable();
   $t->string('seo_location',180)->nullable();$t->text('seo_og_image')->nullable();
   $t->boolean('seo_noindex')->default(false);
  });
  foreach(['pages','projects','services','portfolio_categories','experience_offerings'] as $name) {
   Schema::table($name,function(Blueprint $t){
    $t->string('seo_location',180)->nullable();$t->text('seo_og_image')->nullable();
    $t->boolean('seo_noindex')->default(false);
   });
  }
 }
 public function down(): void {
  foreach(['posts','pages','projects','services','portfolio_categories','experience_offerings'] as $name) {
   Schema::table($name,function(Blueprint $t)use($name){
    if($name==='posts')$t->dropColumn(['category','source_url']);
    $t->dropColumn(['seo_location','seo_og_image','seo_noindex']);
   });
  }
 }
};
