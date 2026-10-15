<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Country extends Model
{
    protected $primaryKey = 'code';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'code',
        'name',
    ];

    public function namePredictions(): BelongsToMany
    {
        return $this->belongsToMany(NamePrediction::class, 'country_name_prediction', 'country_code', 'name_prediction_id')
            ->withPivot('probability')
            ->withTimestamps();
    }

    /**
     * Emoji flag built from the two regional indicator symbols of the ISO code.
     */
    protected function flag(): Attribute
    {
        return Attribute::get(fn () => collect(str_split($this->code))
            ->map(fn (string $letter) => mb_chr(0x1F1E6 + ord($letter) - ord('A')))
            ->implode(''));
    }
}
