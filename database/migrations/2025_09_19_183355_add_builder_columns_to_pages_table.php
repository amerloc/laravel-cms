<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->json('builder_json')->nullable(); // grapesjs projectData
            $table->longText('builder_html')->nullable();
            $table->longText('builder_css')->nullable();
            $table->string('builder_version')->nullable(); // for future migrations
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['builder_json','builder_html','builder_css','builder_version']);
        });
    }
};
