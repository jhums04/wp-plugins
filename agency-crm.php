<?php

/**
 * Plugin Name: Agency CRM
 * Description: CRM for digital agencies.
 * Version: 1.0.0
 * Author: Jhumel Bonganciso
 */

require_once __DIR__ . '/app/Bootstrap.php';
require_once __DIR__ . '/app/PostTypes/LeadPostType.php';
require_once __DIR__ . '/app/Admin/LeadMetaBox.php';
require_once __DIR__ . '/app/Admin/LeadAdminColumns.php';

use AgencyCRM\Database\Migrations;
use AgencyCRM\Bootstrap;

add_action('plugins_loaded', function () {
    (new Bootstrap())->run();
});

register_activation_hook(__FILE__, function () {
    (new Migrations())->run();
});