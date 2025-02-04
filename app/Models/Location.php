<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $connection = 'books_db';
    protected $table = 'Location'; // Specify the table name
    protected $primaryKey = 'LocID';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'Locations',
    ];

    public function books()
    {
        return $this->hasMany(Book::class, 'LocationID', 'LocID');
    }
}
