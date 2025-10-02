<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Asset;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan';

    protected $fillable = ['asset_id','nama_pengaju','catatan','status'];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
}
