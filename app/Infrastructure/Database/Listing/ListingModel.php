<?php

namespace App\Infrastructure\Database\Listing;

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
 * @method static Builder<static>|ListingModel newModelQuery()
 * @method static Builder<static>|ListingModel newQuery()
 * @method static Builder<static>|ListingModel query()
 * @method static Builder<static>|ListingModel whereCreatedAt($value)
 * @method static Builder<static>|ListingModel whereDescription($value)
 * @method static Builder<static>|ListingModel whereId($value)
 * @method static Builder<static>|ListingModel whereTitle($value)
 * @method static Builder<static>|ListingModel whereUpdatedAt($value)
 *
 * @mixin Eloquent
 * @mixin Builder<self>
 */
class ListingModel extends Model
{
    protected $table = 'listings';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = [];
}
