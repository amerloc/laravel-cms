<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();                // e.g. "about", "company/contact"
            $table->string('layout')->default('layouts.app'); // e.g. "layouts.app", "layouts.admin"
            $table->string('view')->nullable();              // optional: custom Blade view override
            $table->string('format')->default('blade');      // blade|markdown|html
            $table->text('excerpt')->nullable();

            $table->longText('content');                     // raw content (blade/markdown/html)

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('og_image_url')->nullable();
            $table->boolean('noindex')->default(false);
            $table->json('meta_json')->nullable();           // any extra name/content pairs

            // Publishing
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();

            // Audit (optional)
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
