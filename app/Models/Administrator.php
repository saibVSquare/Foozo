<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;


class Administrator extends Model implements Authenticatable
{
    use HasFactory, SoftDeletes, AuthenticatableTrait;

    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'is_active',
        'is_verified',
        'role',
        'status'
    ];

    public function isAdmin()
    {
        return $this->role == "admin";
    }

    public function isDriver()
    {
        return $this->role == "driver";
    }

    public function isSupplier()
    {
        return $this->role == "supplier";
    }

    public function isVerified()
    {
        return $this->is_verified;
    }

    public function isActive()
    {
        return $this->is_active;
    }
}
