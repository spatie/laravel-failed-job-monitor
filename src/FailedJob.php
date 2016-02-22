<?php

namespace Spatie\FailedJobsMonitor;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class FailedJob extends Model
{
    protected $table = 'failed_jobs';
    protected $dates = ['failed_at'];

    protected $casts = [
        'payload' => 'json'
    ];


    public static function getAll() : Collection
    {
        return static::query()
            ->orderBy('failed_at', 'desc')
            ->get();
    }


    public static function getFailedToday() : Collection
    {
        return static::query()
            ->orderBy('failed_at', 'desc')
            ->where('failed_at', Carbon::today())
            ->get();
    }


    public static function getLatest() : FailedJob
    {
        return static::query()
            ->orderBy('failed_at', 'desc')
            ->first();
    }

    public function getCommandAttribute() : string
    {
        return get_class(unserialize($this->payload['data']['command']));
    }
}
