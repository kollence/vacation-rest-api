<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'team_user')->withPivot('role');
    }

    public function managers()
    {
        return $this->users()->wherePivot('role', 'manager');
    }

    public function regularUsers()
    {
        return $this->users()->wherePivot('role', 'user');
    }

    public function vacationRequests()
    {
        return $this->hasMany(VacationRequest::class);
    }
}
