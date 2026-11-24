<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('posts',function(Blueprint $t){
            $t->string('category',100)->nullable();$t->text('source_url')->nullable();
            $t->string('source_attribution',180)->nullable();$t->string('author_name',160)->nullable();
            $t->string('seo_locality',100)->nullable();$t->text('seo_share_image')->nullable();
            $t->string('seo_location',180)->nullable();$t->text('seo_og_image')->nullable();
            $t->boolean('seo_noindex')->default(false);
            $t->string('seo_focus_location',120)->nullable();$t->text('seo_image_alt')->nullable();
        });
        foreach(['pages','services','portfolio_categories','projects','experience_offerings'] as $name){
            Schema::table($name,function(Blueprint $t)use($name){
                $t->string('seo_locality',100)->nullable();$t->text('seo_share_image')->nullable();
                $t->string('seo_location',180)->nullable();$t->text('seo_og_image')->nullable();
                $t->boolean('seo_noindex')->default(false);
                if($name!=='experience_offerings'){
                    $t->string('seo_focus_location',120)->nullable();$t->text('seo_image_alt')->nullable();
                }
            });
        }
    }
    public function down(): void {
        Schema::table('posts',fn(Blueprint $t)=>$t->dropColumn([
            'category','source_url','source_attribution','author_name','seo_locality',
            'seo_share_image','seo_location','seo_og_image','seo_noindex','seo_focus_location','seo_image_alt'
        ]));
        foreach(['pages','services','portfolio_categories','projects','experience_offerings'] as $name){
            Schema::table($name,function(Blueprint $t)use($name){
                $columns=['seo_locality','seo_share_image','seo_location','seo_og_image','seo_noindex'];
                if($name!=='experience_offerings')$columns=array_merge($columns,['seo_focus_location','seo_image_alt']);
                $t->dropColumn($columns);
            });
        }
    }
};
