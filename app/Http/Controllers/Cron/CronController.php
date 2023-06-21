<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\Controller;
use App\Models\ServiceAppoinment;
use Illuminate\Http\Request;

class CronController extends Controller
{
    public function activeAppointment()
    {
        $date = \Carbon\Carbon::now()->timezone('+1')->format('Y-m-d');
        $time = \Carbon\Carbon::now()->timezone('+1')->format('H:i:s');
//        $lastHourtime = \Carbon\Carbon::now()->timezone('+1')->subHour('1')->format('H:i:s');

        $StartTime = strtotime($time);
        $finalTime = date("H:i:s", $StartTime - 600);

        $getTodayUpdate = ServiceAppoinment::whereDate('appo_date', $date)
            ->whereBetween('appo_time', [$finalTime, $time])
            ->where('status', 'booked')->update(['status' => 'running']);

        if ($getTodayUpdate) {
            \Log::debug('Running Appointment Running complete');
        }

    }

    public function completeAppointment()
    {
        $date = \Carbon\Carbon::now()->timezone('+1')->format('Y-m-d');
        $time = \Carbon\Carbon::now()->timezone('+1')->format('H:i:s');

        $StartTime = strtotime($time);
        $finalTime = date("H:i:s", $StartTime - 1800);

        $getTodayAppointment = ServiceAppoinment::whereDate('appo_date', $date)
            ->whereTime('appo_time', '<=', $finalTime)
            ->get();

        foreach ($getTodayAppointment as $row) {
            $service_min = @$row->serviceDetails->duration_of_service;
            $diff_time = (strtotime($time) - strtotime($row->appo_time)) / 60;
            if ($diff_time >= $service_min) {
                $updateApoo = ServiceAppoinment::where('id', $row->id)->update(['status' => 'completed']);
            }
        }
        \Log::debug('completed Appointment Running complete');

    }
}
