<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * table tasks
 * @param int id
 * @param string title
 * @param string description
 * @param date due_date
 * @param enum status
 * @param int user_id
 */
class Task extends Model
{
    use HasFactory;
    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'description',
        'due_date',
        'status',
        'user_id'
    ];

    public const STATUS = [
      'completed',
      'in_progress',
      'pending'
    ];

    function user() : BelongsTo {
       return $this->belongsTo(User::class); 
    }
}
