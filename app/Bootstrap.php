<?php

namespace AgencyCRM;

use AgencyCRM\PostTypes\LeadPostType;
use AgencyCRM\Admin\LeadMetaBox;

class Bootstrap
{
    public function run(): void
    {
        (new LeadPostType())->register();
        (new LeadMetaBox())->register();
    }
}