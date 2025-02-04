<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use Notifiable;

    // Explicitly define the table name
    protected $table = 'Students';

    // Explicitly define the primary key
    protected $primaryKey = 'Student_ID';

    // Set the primary key type and incrementing status
    public $incrementing = true; // Primary key is auto-incrementing
    protected $keyType = 'int';  // Primary key type is integer

    // Disable timestamps if the table doesn't have `created_at` and `updated_at`
    public $timestamps = false;

    // Define fillable fields for mass assignment
    protected $fillable = [
        'LastName',
        'FirstName',
        'MiddleName',
        'Suffix',
        'Program_ID',
        'ContactNumber',
        'Email',
        'Overdue_status',
    ];

    // Define the column to use for authentication
    protected $hidden = ['Password']; // Hide the password when serializing

    // Define the column to use for authentication
    public function getAuthPassword()
    {
        return $this->Password; // Use the "Password" column for authentication
    }

    // Define any relationships if applicable (optional example)
    public function program()
    {
        return $this->belongsTo(Program::class, 'Program_ID', 'Program_ID'); // Assuming a `Program` model exists
    }
}
