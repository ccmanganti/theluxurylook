<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::table('projects', function (Blueprint $table) {
            $table->boolean('builder_featured')->default(false);
            $table->string('case_study_type',100)->nullable();
        });
    }
    public function down(): void {
        Schema::table('projects', fn(Blueprint $table) => $table->dropColumn(['builder_featured','case_study_type']));
    }
};
