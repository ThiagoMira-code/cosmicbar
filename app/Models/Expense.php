<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'description',
        'category',
        'amount',
        'type', // fixed | variable | investment
        'expense_date',
        'user_id'
    ];

    protected $casts = [
        'expense_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
