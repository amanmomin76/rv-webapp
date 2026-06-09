<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_code',
        'lead_id',
        'owner_id',
        'project_name',
        'customer_name',
        'source',
        'po_status',
        'project_status',
        'value_amount',
        'currency_code',
        'delivery_date',
        'progress_percent',
    ];

    protected function casts(): array
    {
        return [
            'value_amount' => 'decimal:2',
            'delivery_date' => 'date',
        ];
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class);
    }
}
