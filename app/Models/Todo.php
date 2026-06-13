<?php

namespace App\Models;

use Database\Factories\TodoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['user_id', 'title', 'is_completed'])]
class Todo extends Model
{
    /** @use HasFactory<TodoFactory> */
    use HasFactory, SoftDeletes;

    protected $connection = 'sqlite';

    protected $table = 'todos';

    protected function casts(): array
    {
        return [
            'is_completed' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('is_completed', true);
    }

    public function scopeIncomplete(Builder $query): Builder
    {
        return $query->where('is_completed', false);
    }

    public function scopeForUser(Builder $query, int|User $user): Builder
    {
        return $query->where('user_id', $user instanceof User ? $user->id : $user);
    }

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->latest();
    }

    public function markCompleted(): bool
    {
        return $this->update(['is_completed' => true]);
    }

    public function markIncomplete(): bool
    {
        return $this->update(['is_completed' => false]);
    }

    public function toggleCompletion(): bool
    {
        return $this->update(['is_completed' => ! $this->is_completed]);
    }
}
