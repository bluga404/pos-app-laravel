<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengeluaranBarang extends Model
{
    protected $guarded = ['id'];

    public static function nomorPengeluaran()
    {
        // TRX-0512260001
        $max = self::max('id');
        $prefix = 'TRX-';
        $date = date('dmy');
        $nomor = $prefix . $date . str_pad($max + 1, 4, '0', STR_PAD_LEFT);
        return $nomor;
    }

    public function items()
    {
        return $this->hasMany(ItemPengeluaranBarang::class, 'nomor_pengeluaran', 'nomor_pengeluaran');
    }
}
