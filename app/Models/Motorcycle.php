<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Images;
use App\Models\User;

class Motorcycle extends Model
{
    use HasFactory;
    protected $fillable = ['model', 'make','year','description','user_id'];

    public function images()
    {
        return $this->hasMany(Images::class, 'product_id')->select(['product_id','location']);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->select(['id','phone']);
    }


}
