<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Devotion extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'devotion_date',
        'book',
        'chapter',
        'verse_start',
        'verse_end',
        'verse_text_en',
        'verse_text_id',
        'devotion_title',
        'devotion_text',
        'theme',
        'is_published',
    ];
}