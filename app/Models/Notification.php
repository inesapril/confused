<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Scope untuk notifikasi yang belum dibaca
     */
    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope untuk notifikasi yang sudah dibaca
     */
    public function scopeRead(Builder $query): Builder
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope untuk notifikasi stock menipis
     */
    public function scopeStockLow(Builder $query): Builder
    {
        return $query->whereIn('type', ['stock_low', 'stock_critical']);
    }

    /**
     * Mark notification sebagai dibaca
     */
    public function markAsRead(): bool
    {
        return $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    /**
     * Mark notification sebagai belum dibaca
     */
    public function markAsUnread(): bool
    {
        return $this->update([
            'is_read' => false,
            'read_at' => null
        ]);
    }

    /**
     * Get icon untuk tipe notifikasi
     */
    public function getIconAttribute(): string
    {
        return match ($this->type) {
            'stock_low' => 'alert-triangle',
            'stock_critical' => 'alert-circle',
            'stock_empty' => 'x-circle',
            default => 'bell'
        };
    }

    /**
     * Get color untuk tipe notifikasi
     */
    public function getColorAttribute(): string
    {
        return match ($this->type) {
            'stock_low' => 'warning',
            'stock_critical' => 'danger',
            'stock_empty' => 'danger',
            default => 'info'
        };
    }

    /**
     * Get formatted time ago
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }
}
