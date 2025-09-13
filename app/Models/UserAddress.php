<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'province_id','province_name',
        'regency_id','regency_name',
        'district_id','district_name',
        'village_id','village_name',
        'address1','postcode',
    ];

    public function province() {
        return $this->belongsTo(Province::class,'province_id');
    }

    public function regency() {
        return $this->belongsTo(Regency::class,'regency_id');
    }

    public function district() {
        return $this->belongsTo(District::class,'district_id');
    }

    public function village() {
        return $this->belongsTo(Village::class,'village_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
