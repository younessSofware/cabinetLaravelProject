<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Patient extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'CIN',
        'FullName',
        'PhoneNumber',
        'Age',
        'DateOfBirth',
        'Adress',
        'Password',
        'Password_Confirmation',
        'cin_image'
    ];

    protected $appends = ['cin_image_url'];

    public function getCinImageUrlAttribute()
    {
        if ($this->cin_image) {
            return asset('storage/' . $this->cin_image);
        }
        return null;
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
