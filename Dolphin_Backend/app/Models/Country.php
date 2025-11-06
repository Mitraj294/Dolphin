<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $table = 'countries';

    protected $fillable = [
        'name',
    ];

    /**
     * Get all states in this country.
     */
    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    /**
     * Get all cities in this country.
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    /**
     * Get all organization addresses in this country.
     */
    public function organizationAddresses(): HasMany
    {
        return $this->hasMany(OrganizationAddress::class);
    }

    /**
     * Get all leads in this country.
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
