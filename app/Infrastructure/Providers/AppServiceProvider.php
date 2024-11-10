<?php

namespace App\Infrastructure\Providers;

use App\Domain\Common\Uuid\UuidFactoryInterface;
use App\Domain\Item\ItemRepositoryInterface;
use App\Domain\Listing\ListingRepositoryInterface;
use App\Infrastructure\Common\Uuid\RamseyUuidFactory;
use App\Infrastructure\Database\Model\Item;
use App\Infrastructure\Database\Model\Listing;
use App\Infrastructure\Database\Repository\EloquentItemRepository;
use App\Infrastructure\Database\Repository\EloquentListingRepository;
use Illuminate\Support\ServiceProvider;
use Ramsey\Uuid\UuidFactory;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {


        $this->app->bind(UuidFactoryInterface::class, function () {
            return new RamseyUuidFactory(new UuidFactory());
        });

        $this->app->bind(ListingRepositoryInterface::class, function () {
            /** @var Listing $listing */
            $listing = $this->app->get(Listing::class);

            return new EloquentListingRepository($listing);
        });

        $this->app->bind(ItemRepositoryInterface::class, function () {
            /** @var Item $item */
            $item = $this->app->get(Item::class);

            return new EloquentItemRepository($item);
        });
    }

    public function boot(): void
    {
    }
}
