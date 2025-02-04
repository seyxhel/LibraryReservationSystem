<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Book extends Model
{
    protected $connection = 'books_db';
    protected $table = 'Books'; // Specify the table name
    protected $primaryKey = 'BookID';    // Specify the primary key
    public $incrementing = true;         // Auto-incrementing primary key
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'BookNumber',
        'BookCode',
        'Title',
        'CategoryID',
        'Researcher',
        'Abstract',
        'Institute',
        'LocationID',
        'PublishDate',
        'Status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'CategoryID', 'CategoryID');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'LocationID', 'LocID');
    }
}
