<?php

namespace App\Support;

use App\Models\BusRoute;
use App\Models\CabService;
use App\Models\Flight;
use App\Models\Hotel;
use App\Models\TrainRoute;
use App\Models\TravelAddon;
use App\Models\TravelPackage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

final class InventorySlug
{
    public static function forModel(Model $model): string
    {
        return match (true) {
            $model instanceof Flight => self::forFlight($model),
            $model instanceof Hotel => self::forHotel($model),
            $model instanceof TravelPackage => self::forTravelPackage($model),
            $model instanceof BusRoute => self::forBusRoute($model),
            $model instanceof TrainRoute => self::forTrainRoute($model),
            $model instanceof CabService => self::forCabService($model),
            $model instanceof TravelAddon => self::forTravelAddon($model),
            default => '',
        };
    }

    public static function forFlight(Flight $f): string
    {
        $base = Str::slug(implode('-', array_filter([
            $f->from_city,
            $f->to_city,
            $f->airline,
            $f->flight_number,
        ])));

        return self::finish($base, $f->id);
    }

    public static function forHotel(Hotel $h): string
    {
        $base = Str::slug(implode('-', array_filter([
            $h->name,
            $h->city,
        ])));

        return self::finish($base, $h->id);
    }

    public static function forTravelPackage(TravelPackage $p): string
    {
        $base = Str::slug(implode('-', array_filter([
            $p->name,
            $p->destination,
            $p->category,
        ])));

        return self::finish($base, $p->id);
    }

    public static function forBusRoute(BusRoute $b): string
    {
        $base = Str::slug(implode('-', array_filter([
            $b->from_city,
            $b->to_city,
            $b->operator_name,
        ])));

        return self::finish($base, $b->id);
    }

    public static function forTrainRoute(TrainRoute $t): string
    {
        $base = Str::slug(implode('-', array_filter([
            $t->from_city,
            $t->to_city,
            $t->train_name,
            $t->train_number,
        ])));

        return self::finish($base, $t->id);
    }

    public static function forCabService(CabService $c): string
    {
        $base = Str::slug(implode('-', array_filter([
            $c->service_type,
            $c->from_location,
            $c->to_location,
        ])));

        return self::finish($base, $c->id);
    }

    public static function forTravelAddon(TravelAddon $a): string
    {
        $base = Str::slug($a->category.'-'.$a->name);

        return self::finish($base, $a->id);
    }

    private static function finish(string $base, int $id): string
    {
        $base = $base !== '' ? Str::limit($base, 120, '') : 'item';

        return $base.'-'.$id;
    }
}
