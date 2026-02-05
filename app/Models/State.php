<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class State extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * A state has many cities
     */
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    /**
     * A state has many employees
     */
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
