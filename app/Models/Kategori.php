<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kategori extends Model
{
    use HasFactory;
    
    protected $table = 'kategori';
    protected $fillable = ['nama_kategori'];

    /**
     * Get the assets that belong to this category.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'kategori_id');
    }
}
