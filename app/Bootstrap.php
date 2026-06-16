<?php

namespace AgencyCRM;

use AgencyCRM\PostTypes\LeadPostType;
use AgencyCRM\Admin\LeadMetaBox;
use AgencyCRM\Admin\LeadAdminColumns;

class Bootstrap
{
    public function run(): void
    {
        (new LeadPostType())->register();
        (new LeadMetaBox())->register();
        (new LeadAdminColumns())->register();
    }
}