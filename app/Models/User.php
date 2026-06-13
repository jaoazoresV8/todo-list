<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $connection = 'sqlite';

    protected $table = 'users';

    public function todos(): HasMany
    {
        return $this->hasMany(Todo::class);
    }

    public function completedTodos(): HasMany
    {
        return $this->todos()->completed();
    }

    public function incompleteTodos(): HasMany
    {
        return $this->todos()->incomplete();
    }

    protected function todoCount(): Attribute
    {
        return Attribute::get(fn (): int => $this->todos()->count());
    }

    protected function completedTodoCount(): Attribute
    {
        return Attribute::get(fn (): int => $this->completedTodos()->count());
    }

    protected function incompleteTodoCount(): Attribute
    {
        return Attribute::get(fn (): int => $this->incompleteTodos()->count());
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
