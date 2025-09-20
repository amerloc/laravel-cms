<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    protected $fillable = [
        'title','slug','layout','view','format','excerpt','content',
        'meta_title','meta_description','meta_keywords','canonical_url','og_image_url','noindex','meta_json',
        'status','published_at','created_by_id','updated_by_id',
        'builder_json','builder_html','builder_css','builder_version',
    ];

    protected $casts = [
        'meta_json'   => 'array',
        'builder_json' => 'array',
        'noindex'     => 'boolean',
        'published_at'=> 'datetime',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', 'published')
                 ->when(now(), fn($qq)=>$qq->where(function($x){
                     $x->whereNull('published_at')->orWhere('published_at','<=',now());
                 }));
    }

    public function isPublished(): bool
    {
        return $this->status === 'published' && (! $this->published_at || $this->published_at->lte(now()));
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }
}
