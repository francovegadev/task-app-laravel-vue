<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    use SoftDeletes;

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

    function getTaskImageAttribute() {
      if ($this->image) {
        return $this->image->url();
      }
      return asset('defaults/taskDefault.jpg'); 
    }

    function user() : BelongsTo {
       return $this->belongsTo(User::class); 
    }

    function image() : MorphOne
    {
      return $this->morphOne(Image::class, 'imageable');
      
    }
}
