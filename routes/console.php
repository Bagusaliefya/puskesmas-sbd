<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('puskesmas:rollover')->dailyAt('07:55');
