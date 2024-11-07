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
            return new EloquentListingRepository($this->app->get(Listing::class));
        });

        $this->app->bind(ItemRepositoryInterface::class, function () {
            return new EloquentItemRepository($this->app->get(Item::class));
        });
    }

    public function boot(): void
    {
    }
}
