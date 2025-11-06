<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class State extends Model
{
    protected $table = 'states';

    protected $fillable = [
        'name',
        'country_id',
    ];

    /**
     * Get the country that owns the state.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get all cities in this state.
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    /**
     * Get all organization addresses in this state.
     */
    public function organizationAddresses(): HasMany
    {
        return $this->hasMany(OrganizationAddress::class);
    }

    /**
     * Get all leads in this state.
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
