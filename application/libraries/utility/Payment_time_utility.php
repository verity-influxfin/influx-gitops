<?php

class Payment_time_utility
{
    public function goToPrevious($date)
    {
        $day = substr($date, -2);
        $allNumber = explode("-", $date);
        if (!$allNumber || count($allNumber) < 3) {
            return $date;
        }
        if ($day > 10) {
            $allNumber[2] = '10';
        } elseif ($day < 10) {
            $allNumber[2] = '10';
            if ($allNumber[1] == '01') {
                $allNumber[1] = '12';
                $allNumber[0]--;
            } else {
                $allNumber[1]--;
            }
            if (strlen($allNumber[1]) == 1) {
                $allNumber[1] = '0' . $allNumber[1];
            }
        }
        $date = $allNumber[0] . '-' . $allNumber[1] . '-' . $allNumber[2];
        return $date;
    }

    public function goToNext($date, $forceToNext = false)
    {
        $day = substr($date, -2);
        $allNumber = explode("-", $date);
        if (!$allNumber || count($allNumber) < 3) {
            return $date;
        }
        if ($day > 10 || $day == 10 && $forceToNext) {
            $allNumber[2] = '10';
            if ($allNumber[1] == '12') {
                $allNumber[1] = '01';
                $allNumber[0]++;
            } else {
                $allNumber[1]++;
            }
            if (strlen($allNumber[1]) == 1) {
                $allNumber[1] = '0' . $allNumber[1];
            }
        } elseif ($day < 10) {
            $allNumber[2] = '10';
        }
        $date = $allNumber[0] . '-' . $allNumber[1] . '-' . $allNumber[2];
        return $date;
    }

    public function goToMostRecent($date)
    {
        $day = substr($date, -2);
        $allNumber = explode("-", $date);
        if (!$allNumber || count($allNumber) < 3) {
            return $date;
        }
        if ($day > 10) {
            $allNumber[2] = '10';
        } elseif ($day < 10) {
            $allNumber[2] = '10';
            if ($allNumber[1] == '01') {
                $allNumber[1] = '12';
                $allNumber[0]--;
            } else {
                $allNumber[1]--;
            }
            if (strlen($allNumber[1]) == 1) {
                $allNumber[1] = '0' . $allNumber[1];
            }
        }
        $date = $allNumber[0] . '-' . $allNumber[1] . '-' . $allNumber[2];
        return $date;
    }

    public function measureMonthGaps($startAt, $endAt)
    {
        if ($startAt > $endAt) {
            $temp = $startAt;
            $startAt = $endAt;
            $endAt = $temp;
        }

        $startTime = explode("-", $startAt);
        $endTime = explode("-", $endAt);
        $yearDiff = $endTime[0] - $startTime[0];
        if ($startTime[1] > $endTime[1]) {
            $monthDiff = 12 - $startTime[1] + $endTime[1];
            $yearDiff--;
        } else {
            $monthDiff = $endTime[1] - $startTime[1];
        }

        return $yearDiff * 12 + $monthDiff;
    }
}