<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('preview_title')->nullable()->after('title');
            $table->text('project_brief')->nullable();
            $table->longText('design_approach')->nullable();
            $table->text('scope_of_work')->nullable();
            $table->text('result_statement')->nullable();
            $table->boolean('results_verified')->default(false);
            $table->json('metrics')->nullable();
            $table->json('before_gallery')->nullable();
            $table->text('client_quote')->nullable();
            $table->string('quote_attribution')->nullable();
        });
    }
    public function down(): void
    {
        Schema::table('projects', fn (Blueprint $table) => $table->dropColumn([
            'preview_title','project_brief','design_approach','scope_of_work',
            'result_statement','results_verified','metrics','before_gallery',
            'client_quote','quote_attribution',
        ]));
    }
};
