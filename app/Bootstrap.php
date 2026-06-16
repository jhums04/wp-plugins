<?php

namespace AgencyCRM;

use AgencyCRM\PostTypes\LeadPostType;
use AgencyCRM\Admin\LeadMetaBox;
use AgencyCRM\Admin\LeadAdminColumns;

use AgencyCRM\Repositories\ActivityRepository;
use AgencyCRM\Services\ActivityService;
use AgencyCRM\Admin\LeadActivityMetaBox;
/**
 * Hooks
 */
use AgencyCRM\Hooks\LeadHooks;


class Bootstrap
{
    public ActivityService $activity;

    public function run(): void
    {
        $this->activity = new ActivityService(
            new \AgencyCRM\Repositories\ActivityRepository()
        );

        (new LeadPostType())->register();
        (new LeadMetaBox())->register();
        (new LeadAdminColumns())->register();
        (new LeadActivityMetaBox())->register();
        (new LeadHooks())->register();
    }
}