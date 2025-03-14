<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['text', 'type', 'options'];

    protected $casts = [
        'options' => 'array',
    ];

    public function userAnswers(): HasMany
    {
        return $this->hasMany(UserAnswer::class);
    }
}
