<?php

namespace CodeLabX\XtendLaravel\Base;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class XtendPackage
 *
 * @property string $name
 * @property string $version
 * @property array $data
 * @property bool $is_enabled
 *
 * @mixin Builder
 */
class XtendPackage extends Model
{
    protected $fillable = [
        'name',
        'version',
        'data',
        'namespace',
        'is_enabled',
    ];

    protected $casts = [
        'data' => 'array',
        'is_enabled' => 'boolean',
    ];

    public function scopeEnabled($query): Builder
    {
        return $query->where('is_enabled', true);
    }
}
