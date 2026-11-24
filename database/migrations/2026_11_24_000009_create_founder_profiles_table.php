<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('founder_profiles',function(Blueprint $t){
            $t->id(); $t->string('name'); $t->string('role'); $t->string('eyebrow')->nullable();
            $t->string('headline')->nullable(); $t->text('lead')->nullable(); $t->text('short_bio')->nullable();
            $t->longText('career_bio')->nullable(); $t->longText('approach_bio')->nullable(); $t->longText('beyond_bio')->nullable();
            $t->string('founding_year',30)->nullable(); $t->string('experience_label',60)->nullable();
            foreach(['portrait_url','portrait_upload','secondary_photo_url','secondary_photo_upload','headshot_url','headshot_upload','interview_url','interview_poster_url'] as $name) $t->text($name)->nullable();
            $t->json('principles')->nullable();$t->longText('source_notes')->nullable();
            $t->boolean('is_published')->default(false); $t->boolean('content_verified')->default(false);$t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('founder_profiles'); }
};
