<?php

namespace AgencyCRM\Admin;

use AgencyCRM\Services\ActivityService;
use AgencyCRM\Repositories\ActivityRepository;
use AgencyCRM\Constants\ActivityTypes;

class LeadMetaBox
{
    private ActivityService $activity;

    public function __construct()
    {
        $this->activity = new ActivityService(
            new ActivityRepository()
        );
    }

    public function register(): void
    {
        add_action('add_meta_boxes', [$this, 'addMetaBox']);
        add_action('save_post_lead', [$this, 'saveMetaBox']);
    }

    public function addMetaBox(): void
    {
        add_meta_box(
            'agency_crm_lead_details',
            'Lead Details',
            [$this, 'renderMetaBox'],
            'lead',
            'normal',
            'high'
        );
    }

    public function renderMetaBox($post): void
    {
        wp_nonce_field('agency_crm_lead_nonce', 'agency_crm_lead_nonce');

        $contactName = get_post_meta($post->ID, 'contact_name', true);
        $email       = get_post_meta($post->ID, 'email', true);
        $phone       = get_post_meta($post->ID, 'phone', true);
        $source      = get_post_meta($post->ID, 'source', true);
        $stage       = get_post_meta($post->ID, 'stage', true);
        $budget      = get_post_meta($post->ID, 'budget', true);
        $service     = get_post_meta($post->ID, 'service', true);
        $assignedUser = get_post_meta($post->ID, 'assigned_user', true);
        $callNotes = get_post_meta($post->ID, 'call_notes', true);

        $users = get_users();
?>

        <table class="form-table">

            <tr>
                <th>Contact Name</th>
                <td>
                    <input type="text" name="contact_name"
                        value="<?php echo esc_attr($contactName); ?>"
                        class="regular-text">
                </td>
            </tr>

            <tr>
                <th>Email</th>
                <td>
                    <input type="email" name="email"
                        value="<?php echo esc_attr($email); ?>"
                        class="regular-text">
                </td>
            </tr>

            <tr>
                <th>Phone</th>
                <td>
                    <input type="text" name="phone"
                        value="<?php echo esc_attr($phone); ?>"
                        class="regular-text">
                </td>
            </tr>

            <tr>
                <th>Source</th>
                <td>
                    <select name="source">
                        <option value="">Select Source</option>
                        <option value="website" <?php selected($source, 'website'); ?>>Website</option>
                        <option value="facebook" <?php selected($source, 'facebook'); ?>>Facebook</option>
                        <option value="google" <?php selected($source, 'google'); ?>>Google</option>
                        <option value="referral" <?php selected($source, 'referral'); ?>>Referral</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th>Stage</th>
                <td>
                    <select name="stage">
                        <option value="new" <?php selected($stage, 'new'); ?>>New</option>
                        <option value="qualified" <?php selected($stage, 'qualified'); ?>>Qualified</option>
                        <option value="discovery" <?php selected($stage, 'discovery'); ?>>Discovery</option>
                        <option value="proposal_sent" <?php selected($stage, 'proposal_sent'); ?>>Proposal Sent</option>
                        <option value="negotiation" <?php selected($stage, 'negotiation'); ?>>Negotiation</option>
                        <option value="won" <?php selected($stage, 'won'); ?>>Won</option>
                        <option value="lost" <?php selected($stage, 'lost'); ?>>Lost</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Service</th>
                <td>
                    <select name="service">
                        <option value="new" <?php selected($service, 'website'); ?>>Website Development</option>
                        <option value="qualified" <?php selected($service, 'seo'); ?>>SEO</option>
                        <option value="discovery" <?php selected($service, 'google_ads'); ?>>Google Ads</option>
                        <option value="proposal_sent" <?php selected($service, 'automation'); ?>>CRM and Automation</option>
                    </select>
                </td>
            </tr>

            <tr>
                <th>Budget</th>
                <td>
                    <select name="budget">

                        <option value="">
                            Select Budget
                        </option>

                        <option value="20k-50k"
                            <?php selected($budget, '20k-50k'); ?>>
                            ₱20,000 - ₱50,000
                        </option>

                        <option value="50k-100k"
                            <?php selected($budget, '50k-100k'); ?>>
                            ₱50,000 - ₱100,000
                        </option>

                        <option value="100k-250k"
                            <?php selected($budget, '100k-250k'); ?>>
                            ₱100,000 - ₱250,000
                        </option>

                        <option value="250k+"
                            <?php selected($budget, '250k+'); ?>>
                            ₱250,000+
                        </option>

                    </select>
                </td>
            </tr>

            <tr>
                <th>Assigned User</th>
                <td>
                    <select name="assigned_user">
                        <option value="">Select User</option>

                        <?php foreach ($users as $user): ?>
                            <option value="<?php echo $user->ID; ?>"
                                <?php selected($assignedUser, $user->ID); ?>>
                                <?php echo esc_html($user->display_name); ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </td>
            </tr>

            <tr>
                <th>Call Notes</th>
                <td>
                    <textarea
                        name="call_notes"
                        rows="8"
                        style="width:100%;"><?php echo esc_textarea($callNotes); ?></textarea>
                </td>
            </tr>

        </table>

<?php
    }

    public function saveMetaBox(int $postId): void
    {
        $post = get_post($postId);

        if (!$post || $post->post_type !== 'lead') {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (wp_is_post_revision($postId)) {
            return;
        }

        if (
            !isset($_POST['agency_crm_lead_nonce']) ||
            !wp_verify_nonce($_POST['agency_crm_lead_nonce'], 'agency_crm_lead_nonce')
        ) {
            return;
        }

        $fields = [
            'contact_name',
            'email',
            'phone',
            'source',
            'stage',
            'budget',
            'assigned_user',
            'call_notes',
        ];

        $generalChanged = false;

        $oldStage = get_post_meta($postId, 'stage', true);
        $newStage = $_POST['stage'] ?? null;

        foreach ($fields as $field) {

            if (!isset($_POST[$field])) {
                continue;
            }

            $old = get_post_meta($postId, $field, true);
            $raw = $_POST[$field] ?? '';

            switch ($field) {

                case 'assigned_user':
                    $new = (int) $raw;
                    break;

                default:
                    $new = sanitize_text_field($raw);
                    break;
            }

            if ($old !== $new) {
                $generalChanged = true;
            }

            update_post_meta($postId, $field, $new);
        }

        // Stage change event
        if (!empty($newStage) && $oldStage !== $newStage) {
            $this->activity->log([
                'entity_type'   => 'lead',
                'entity_id'     => $postId,
                'activity_type' => ActivityTypes::STAGE_CHANGED,
                'description'   => "Stage changed from {$oldStage} to {$newStage}",
            ]);
        }

        // General update event
        if ($generalChanged) {
            $this->activity->log([
                'entity_type'   => 'lead',
                'entity_id'     => $postId,
                'activity_type' => ActivityTypes::LEAD_UPDATED,
                'description'   => 'Lead details updated',
            ]);
        }
    }
}
