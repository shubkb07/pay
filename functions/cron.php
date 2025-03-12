<?php

/**
 * Cron Job.
 */

/**
 * Daily Cron Job.
 */
function daily_cron() {
    global $pay;
    $pay->update_latest_currency_rates();
}