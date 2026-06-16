<?php

namespace AgencyCRM\Admin;

class LeadMetaBox
{
    public function register(): void
    {
        add_action(
            'add_meta_boxes',
            [$this, 'addMetaBox']
        );

        add_action(
            'save_post_lead',
            [$this, 'saveMetaBox']
        );
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
        wp_nonce_field(
            'agency_crm_lead_nonce',
            'agency_crm_lead_nonce'
        );

        $contactName = get_post_meta(
            $post->ID,
            'contact_name',
            true
        );

        $email = get_post_meta(
            $post->ID,
            'email',
            true
        );

        $phone = get_post_meta(
            $post->ID,
            'phone',
            true
        );

        $source = get_post_meta(
            $post->ID,
            'source',
            true
        );

        $stage = get_post_meta(
            $post->ID,
            'stage',
            true
        );

        $budget = get_post_meta(
            $post->ID,
            'budget',
            true
        );

        $assignedUser = get_post_meta(
            $post->ID,
            'assigned_user',
            true
        );

        $users = get_users();
        ?>

        <table class="form-table">

            <tr>
                <th>Contact Name</th>
                <td>
                    <input
                        type="text"
                        name="contact_name"
                        value="<?php echo esc_attr($contactName); ?>"
                        class="regular-text">
                </td>
            </tr>

            <tr>
                <th>Email</th>
                <td>
                    <input
                        type="email"
                        name="email"
                        value="<?php echo esc_attr($email); ?>"
                        class="regular-text">
                </td>
            </tr>

            <tr>
                <th>Phone</th>
                <td>
                    <input
                        type="text"
                        name="phone"
                        value="<?php echo esc_attr($phone); ?>"
                        class="regular-text">
                </td>
            </tr>

            <tr>
                <th>Source</th>
                <td>
                    <select name="source">

                        <option value="">
                            Select Source
                        </option>

                        <option
                            value="website"
                            <?php selected($source, 'website'); ?>>
                            Website
                        </option>

                        <option
                            value="facebook"
                            <?php selected($source, 'facebook'); ?>>
                            Facebook
                        </option>

                        <option
                            value="google"
                            <?php selected($source, 'google'); ?>>
                            Google
                        </option>

                        <option
                            value="referral"
                            <?php selected($source, 'referral'); ?>>
                            Referral
                        </option>

                    </select>
                </td>
            </tr>

            <tr>
                <th>Stage</th>
                <td>
                    <select name="stage">

                        <option value="new" <?php selected($stage, 'new'); ?>>
                            New
                        </option>

                        <option value="qualified" <?php selected($stage, 'qualified'); ?>>
                            Qualified
                        </option>

                        <option value="discovery" <?php selected($stage, 'discovery'); ?>>
                            Discovery
                        </option>

                        <option value="proposal_sent" <?php selected($stage, 'proposal_sent'); ?>>
                            Proposal Sent
                        </option>

                        <option value="negotiation" <?php selected($stage, 'negotiation'); ?>>
                            Negotiation
                        </option>

                        <option value="won" <?php selected($stage, 'won'); ?>>
                            Won
                        </option>

                        <option value="lost" <?php selected($stage, 'lost'); ?>>
                            Lost
                        </option>

                    </select>
                </td>
            </tr>

            <tr>
                <th>Budget</th>
                <td>
                    <input
                        type="number"
                        name="budget"
                        value="<?php echo esc_attr($budget); ?>">
                </td>
            </tr>

            <tr>
                <th>Assigned User</th>
                <td>
                    <select name="assigned_user">

                        <option value="">
                            Select User
                        </option>

                        <?php foreach ($users as $user): ?>

                            <option
                                value="<?php echo $user->ID; ?>"
                                <?php selected(
                                    $assignedUser,
                                    $user->ID
                                ); ?>>

                                <?php echo esc_html(
                                    $user->display_name
                                ); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>
                </td>
            </tr>

        </table>

        <?php
    }

    public function saveMetaBox(int $postId): void
    {
        if (
            !isset($_POST['agency_crm_lead_nonce'])
        ) {
            return;
        }

        if (
            !wp_verify_nonce(
                $_POST['agency_crm_lead_nonce'],
                'agency_crm_lead_nonce'
            )
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
        ];

        foreach ($fields as $field) {
            update_post_meta(
                $postId,
                $field,
                sanitize_text_field(
                    $_POST[$field] ?? ''
                )
            );
        }
    }
}