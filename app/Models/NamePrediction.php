<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class NamePrediction extends Model
{
    protected $fillable = [
        'name',
        'sample_count',
    ];

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class, 'country_name_prediction', 'name_prediction_id', 'country_code')
            ->withPivot('probability')
            ->withTimestamps()
            ->orderByPivot('probability', 'desc');
    }
}
