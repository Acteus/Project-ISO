<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\AuditLogFactory::new();
    }

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'ip_address',
        'old_values',
        'new_values',
        'resource_type',
        'resource_id',
        'metadata',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        // user_id can reference either Admin or User
        // Check if it's an admin by checking if the ID exists in admins table
        if ($this->user_id && \App\Models\Admin::find($this->user_id)) {
            return $this->belongsTo(Admin::class, 'user_id');
        }
        return $this->belongsTo(User::class, 'user_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }
}
