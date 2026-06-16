<?php
/**
 * Plugin Name: Agency CRM
 * Description: CRM for digital agencies.
 * Version: 1.0.0
 * Author: Jhumel Bonganciso
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Core plugin bootstrap
 */
require_once __DIR__ . '/app/Bootstrap.php';

/**
 * Post Types
 */
require_once __DIR__ . '/app/PostTypes/LeadPostType.php';

/**
 * Admin
 */
require_once __DIR__ . '/app/Admin/LeadMetaBox.php';
require_once __DIR__ . '/app/Admin/LeadAdminColumns.php';
require_once __DIR__ . '/app/Admin/LeadActivityMetaBox.php';

/**
 * CRM Services
 */
require_once __DIR__ . '/app/Repositories/ActivityRepository.php';
require_once __DIR__ . '/app/Services/ActivityService.php';

/**
 * Database
 */
require_once __DIR__ . '/app/Database/Migrations.php';

use AgencyCRM\Bootstrap;
use AgencyCRM\Database\Migrations;

/**
 * Run plugin
 */
add_action('plugins_loaded', function () {
    (new Bootstrap())->run();
});

/**
 * Plugin activation (create tables)
 */
register_activation_hook(__FILE__, function () {
    (new Migrations())->run();
});