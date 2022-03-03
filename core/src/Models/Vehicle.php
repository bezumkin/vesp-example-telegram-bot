<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $brand
 * @property string $model
 * @property string $type
 * @property string $fuel
 * @property int $doors
 * @property int $seats
 * @property string $gearbox
 * @property ?array $properties
 * @property bool $active
 */
class Vehicle extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $casts = [
        'properties' => 'json',
        'active' => 'bool',
    ];
}