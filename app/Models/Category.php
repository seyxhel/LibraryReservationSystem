<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $connection = 'books_db';
    protected $table = 'Category'; // Specify the table name
    protected $primaryKey = 'CategoryID';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'Categories',
    ];

    public function books()
    {
        return $this->hasMany(Book::class, 'CategoryID', 'CategoryID');
    }
}
