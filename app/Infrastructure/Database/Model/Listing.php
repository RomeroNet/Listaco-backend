<?php

namespace App\Infrastructure\Database\Model;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $title
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder<static>|Listing newModelQuery()
 * @method static Builder<static>|Listing newQuery()
 * @method static Builder<static>|Listing query()
 * @method static Builder<static>|Listing whereCreatedAt($value)
 * @method static Builder<static>|Listing whereDescription($value)
 * @method static Builder<static>|Listing whereId($value)
 * @method static Builder<static>|Listing whereTitle($value)
 * @method static Builder<static>|Listing whereUpdatedAt($value)
 *
 * @mixin Eloquent
 * @mixin Builder<self>
 */
class Listing extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
}
