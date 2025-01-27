<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    // Specify the table name if it differs from "admins"
    protected $table = 'Admins';

    // Specify the primary key if it differs from "id"
    protected $primaryKey = 'Admin_ID';

    // Disable timestamps if not present in the table
    public $timestamps = false;

    protected $fillable = [
        'Email',
        'School_ID',
        'LastName',
        'FirstName',
        'MiddleName',
        'Suffix',
        'Gender',
        'ContactNumber',
        'Password',
        'Status',
        'AccountCreated',
        'UpdatedAt',
    ];

    // Specify the hidden attributes
    protected $hidden = ['Password'];

    // Define the authentication password column
    public function getAuthPassword()
    {
        return $this->Password;
    }
}
