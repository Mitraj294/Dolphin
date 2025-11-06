<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $table = 'cities';

    protected $fillable = [
        'name',
        'state_id',
        'country_id',
    ];

    /**
     * Get the state that owns the city.
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    /**
     * Get the country that owns the city.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get all organization addresses in this city.
     */
    public function organizationAddresses(): HasMany
    {
        return $this->hasMany(OrganizationAddress::class);
    }

    /**
     * Get all leads in this city.
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
