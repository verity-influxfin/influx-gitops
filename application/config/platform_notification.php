<?php

# 推播的相關設定
abstract class NotificationTargetCategory
{
    const Investment = 1;
    const Loan = 2;
    const All = 3;
}

abstract class NotificationType
{
    const Manual = 1;
    const RoutineReminder = 2;
}

abstract class NotificationStatus
{
    const Pending = 0;
    const Accepted = 1;
    const Rejected = 2;
    const Sent = 3;
    const Canceled = 4;
}
