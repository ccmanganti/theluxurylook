<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::table('posts', function (Blueprint $t) {
   $t->string('category',100)->nullable();
   $t->text('source_url')->nullable();
   $t->string('author_name',160)->nullable();
   $t->string('source_attribution',180)->nullable();
  });
  foreach(['posts','pages','projects','services','portfolio_categories','experience_offerings'] as $table) {
   Schema::table($table,function(Blueprint $t){
    $t->string('seo_location',180)->nullable();
    $t->text('seo_og_image')->nullable();
    $t->boolean('seo_noindex')->default(false);
    $t->string('seo_locality',100)->nullable();
    $t->text('seo_share_image')->nullable();
    $t->string('seo_focus_location',120)->nullable();
    $t->text('seo_image_alt')->nullable();
   });
  }
 }
 public function down(): void {
  foreach(['posts','pages','projects','services','portfolio_categories','experience_offerings'] as $table) {
   Schema::table($table,function(Blueprint $t){
    $t->dropColumn(['seo_location','seo_og_image','seo_noindex',
        'seo_locality','seo_share_image','seo_focus_location','seo_image_alt']);
   });
  }
  Schema::table('posts',fn(Blueprint $t)=>$t->dropColumn(['category','source_url','author_name','source_attribution']));
 }
};
