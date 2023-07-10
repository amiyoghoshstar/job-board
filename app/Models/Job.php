<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $casts = [
        'skills' => 'array',
        'locations' => 'array',
        'category' => 'array',
    ];

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'category',
        'apply_url',
        'apply_count',
        'position',
        'salary',
        'locations',
        'skills',
        'unique_id',
        'source',
        'status',
        'seo_image',
        'seo_title',
        'seo_keywords',
        'seo_description'
    ];



}
