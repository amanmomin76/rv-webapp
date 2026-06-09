<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_code',
        'customer_name',
        'customer_mobile',
        'customer_email',
        'company_name',
        'city',
        'state',
        'country',
        'buyer_type',
        'gst_or_website',
        'requirement_message',
        'requirement_product',
        'requirement_quantity',
        'requirement_budget',
        'requirement_delivery',
        'requirement_category',
        'source',
        'external_source_id',
        'source_payload',
        'status',
        'manager_user_id',
        'assigned_to_user_id',
        'inquiry_at',
        'imported_at',
    ];

    protected function casts(): array
    {
        return [
            'source_payload' => 'array',
            'inquiry_at' => 'datetime',
            'imported_at' => 'datetime',
        ];
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_user_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(LeadNote::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(LeadDocument::class);
    }
}
