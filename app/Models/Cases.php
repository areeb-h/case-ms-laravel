<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cases extends Model
{
    use HasFactory;

    const CASE_TYPES = [
        'CIVIL' => 'Civil',
        'HOMICIDE' => 'Homicide',
        'THEFT' => 'Theft',
        'ASSAULT' => 'Assault',
        'DRUG_POSSESSION' => 'Drug Possession',
        'DUI' => 'Driving Under Influence',
        'FRAUD' => 'Fraud',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'case_number',
        'case_title',
        'case_type',
        'case_role',
        'client_id',
        'lawyer_id',
        'status',
        'description',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Check if the case is of a defendant
     *
     * @return bool
     */
    public function isDefendantCase()
    {
        return !$this->is_appellant;
    }

    public function isAppellantCase()
    {
        return $this->is_appellant;
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function lawyer()
    {
        return $this->belongsTo(User::class, 'lawyer_id');
    }

    // Define your relationships, methods, and other logic here
    // For example, if a case belongs to a user:
    public function user()
    {
         return $this->belongsTo(User::class);
    }
}
