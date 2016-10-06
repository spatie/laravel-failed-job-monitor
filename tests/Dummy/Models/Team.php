<?php

namespace Spatie\FailedJobMonitor\Test\Dummy\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Team extends Model
{
    use Notifiable;
    public $table = 'spatie_failed_job_monitor_test_teams';
    public function scopeCanBeNotifiedAboutFailedJobs(Builder $builder)
    {
        return $builder->where('id', 1);
    }

    public function routeNotificationForSlack()
    {
        return '';
    }
}
