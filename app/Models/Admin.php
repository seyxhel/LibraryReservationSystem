<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'Admins';
    protected $primaryKey = 'Admin_ID';
    public $timestamps = false;

    protected $fillable = [
        'Email', 'School_ID', 'LastName', 'FirstName', 'MiddleName',
        'Suffix', 'Gender', 'ContactNumber', 'Password', 'Status', 'AccountCreated'
    ];

    protected $hidden = [
        'Password',
    ];

    /**
     * Override default authentication column.
     */
    public function getAuthIdentifierName()
    {
        return 'Admin_ID'; // Change this if your auth column is different
    }

    /**
     * Override default password field.
     */
    public function getAuthPassword()
    {
        return $this->Password;
    }
}

