<?php

namespace Spatie\FailedJobMonitor;

use Illuminate\Database\Eloquent\Builder;

interface CanBeNotifiedAboutFailedJobs
{
    public function scopeCanBeNotifiedAboutFailedJobs(Builder $builder);
}