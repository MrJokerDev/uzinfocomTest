<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'hash',
        'file',
        'filename'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'hash',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function user()
    {
        return $this->belongsToMany(User::class, 'user_file', 'file_id', 'user_id')->withTimestamps();
    }
}
