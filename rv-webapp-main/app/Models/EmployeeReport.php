<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'manager_id',
        'report_date',
        'work_started_at',
        'work_ended_at',
        'leads_contacted',
        'follow_ups_completed',
        'notes_added',
        'quotations_shared',
        'calls_made',
        'whatsapp_messages',
        'emails_sent',
        'summary',
        'issues',
        'tomorrow_plan',
        'status',
        'manager_feedback',
    ];

    protected function casts(): array
    {
        return [
            'report_date' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
