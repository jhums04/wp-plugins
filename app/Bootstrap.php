<?php

namespace AgencyCRM;

use AgencyCRM\PostTypes\LeadPostType;
use AgencyCRM\PostTypes\ProposalPostType;
use AgencyCRM\Admin\LeadMetaBox;
use AgencyCRM\Admin\LeadAdminColumns;
use AgencyCRM\Admin\ProposalAdminColumns;
use AgencyCRM\Services\ActivityService;
use AgencyCRM\Admin\LeadActivityMetaBox;

/**
 * Hooks
 */

use AgencyCRM\Hooks\LeadHooks;

use AgencyCRM\Forms\ConsultationForm;


use AgencyCRM\Controllers\LeadController;
use AgencyCRM\Controllers\ProposalController;
use AgencyCRM\Admin\ProposalMetaBox;

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
        (new ConsultationForm())->register();
        (new LeadController())->register();
        (new ProposalPostType())->register();
        (new ProposalController())->register();
        (new ProposalAdminColumns())->register();
        (new ProposalMetaBox())->register();
    }
}
