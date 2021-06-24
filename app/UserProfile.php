<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    protected $table = 'user_profile';
    protected $fillable = [
        'phone', 'mobile_phone', 'about', 'social_networks'
    ];



    public function user()
    {
        return $this->BelongsTo(User::class);
    }
}
