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
require_once __DIR__ . '/app/PostTypes/ProposalPostType.php';

/**
 * Admin
 */
require_once __DIR__ . '/app/Admin/LeadMetaBox.php';
require_once __DIR__ . '/app/Admin/ProposalMetaBox.php';
require_once __DIR__ . '/app/Admin/LeadAdminColumns.php';
require_once __DIR__ . '/app/Admin/ProposalAdminColumns.php';
require_once __DIR__ . '/app/Admin/LeadActivityMetaBox.php';

/**
 * CRM Services
 */
require_once __DIR__ . '/app/Repositories/ActivityRepository.php';
require_once __DIR__ . '/app/Repositories/LeadRepository.php';
require_once __DIR__ . '/app/Repositories/ProposalRepository.php';
require_once __DIR__ . '/app/Services/ActivityService.php';

/**
 * Database
 */
require_once __DIR__ . '/app/Database/Migrations.php';

/**
 * Hooks
 */
require_once __DIR__ . '/app/Hooks/LeadHooks.php';

/**
 * Activity Types
 */
require_once __DIR__ . '/app/Constants/ActivityTypes.php';

/**
 * forms
 */
require_once __DIR__ . '/app/Forms/ConsultationForm.php';


/**
 * Controllers
 */
require_once __DIR__ . '/app/Controllers/LeadController.php';
require_once __DIR__ . '/app/Controllers/ProposalController.php';

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
