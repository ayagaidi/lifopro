<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardFieldVisibility extends Model
{
    use HasFactory;

    protected $table = 'card_field_visibility';

    protected $fillable = [
        'field_name',
        'field_label',
        'visible',
        'order'
    ];

    public $timestamps = true;

    /**
     * Get all visible fields ordered by display order
     */
    public static function getVisibleFields()
    {
        return self::where('visible', true)
            ->orderBy('order')
            ->get();
    }

    /**
     * Get all fields ordered by display order
     */
    public static function getAllFields()
    {
        return self::orderBy('order')
            ->get();
    }
}
