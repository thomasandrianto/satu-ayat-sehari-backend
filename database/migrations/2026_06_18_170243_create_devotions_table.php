<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devotions', function (Blueprint $table) {

            $table->uuid('id')->primary();

            $table->date('devotion_date')->unique();

            $table->string('book');

            $table->unsignedInteger('chapter');

            $table->unsignedInteger('verse_start');

            $table->unsignedInteger('verse_end')->nullable();

            $table->text('verse_text_en');

            $table->text('verse_text_id');

            $table->string('devotion_title');

            $table->longText('devotion_text');

            $table->string('theme')->nullable();

            $table->boolean('is_published')
                ->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devotions');
    }
};
