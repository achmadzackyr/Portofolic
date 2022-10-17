<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portofolio extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function portofolio_images()
    {
        return $this->hasMany(PortofolioImage::class);
    }

    public function portofolio_skills()
    {
        return $this->hasMany(PortofolioSkill::class);
    }

    public function portofolio_type()
    {
        return $this->belongsTo(PortofolioType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
