<?php

namespace AgencyCRM;

use AgencyCRM\PostTypes\LeadPostType;
use AgencyCRM\Admin\LeadMetaBox;
use AgencyCRM\Admin\LeadAdminColumns;

use AgencyCRM\Repositories\ActivityRepository;
use AgencyCRM\Services\ActivityService;
use AgencyCRM\Admin\LeadActivityMetaBox;

class Bootstrap
{
    public static ActivityService $activity;

    public function run(): void
    {
        self::$activity = new ActivityService(
            new ActivityRepository()
        );

        (new LeadPostType())->register();
        (new LeadMetaBox())->register();
        (new LeadAdminColumns())->register();
        (new LeadActivityMetaBox())->register();
    }
}