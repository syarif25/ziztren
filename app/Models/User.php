<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['username', 'email', 'password', 'level', 'status'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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

    public function isAdmin(): bool
    {
        return $this->level === 'admin';
    }

    public function isOperatorPutra(): bool
    {
        return $this->level === 'operator_putra';
    }

    public function isOperatorPutri(): bool
    {
        return $this->level === 'operator_putri';
    }

    /**
     * Mengembalikan 'L', 'P', atau null (jika admin bisa semua)
     */
    public function getGenderScope(): ?string
    {
        if ($this->level === 'operator_putra') {
            return 'L';
        }
        if ($this->level === 'operator_putri') {
            return 'P';
        }
        return null;
    }
}
