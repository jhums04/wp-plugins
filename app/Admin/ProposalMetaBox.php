<?php

namespace AgencyCRM\Admin;

class ProposalMetaBox
{
    public function register(): void
    {
        add_action(
            'add_meta_boxes',
            [$this, 'addMetaBox']
        );

        add_action(
            'save_post_proposal',
            [$this, 'saveMetaBox']
        );
    }

    public function addMetaBox(): void
    {
        add_meta_box(
            'agency_crm_proposal_details',
            'Proposal Details',
            [$this, 'renderMetaBox'],
            'proposal',
            'normal',
            'high'
        );
    }

    public function renderMetaBox($post): void
    {
        wp_nonce_field(
            'agency_crm_proposal_nonce',
            'agency_crm_proposal_nonce'
        );

        $leadId = get_post_meta(
            $post->ID,
            'lead_id',
            true
        );

        $status = get_post_meta(
            $post->ID,
            'status',
            true
        );

        $version = get_post_meta(
            $post->ID,
            'version',
            true
        );

        $proposalContent = $post->post_content;

?>

        <table class="form-table">

            <tr>
                <th>Lead ID</th>
                <td>
                    <?php echo esc_html($leadId); ?>
                </td>
            </tr>

            <tr>
                <th>Status</th>
                <td>
                    <select name="status">

                        <option value="draft" <?php selected($status, 'draft'); ?>>
                            Draft
                        </option>

                        <option value="reviewing" <?php selected($status, 'reviewing'); ?>>
                            Reviewing
                        </option>

                        <option value="sent" <?php selected($status, 'sent'); ?>>
                            Sent
                        </option>

                        <option value="approved" <?php selected($status, 'approved'); ?>>
                            Approved
                        </option>

                        <option value="rejected" <?php selected($status, 'rejected'); ?>>
                            Rejected
                        </option>

                    </select>
                </td>
            </tr>

            <tr>
                <th>Version</th>
                <td>
                    <input
                        type="number"
                        name="version"
                        value="<?php echo esc_attr($version); ?>">
                </td>
            </tr>

        </table>

        <h2>Proposal Content</h2>

        <textarea
            name="proposal_content"
            style="
                width:100%;
                min-height:700px;
                font-family:monospace;
            "><?php echo esc_textarea($proposalContent); ?></textarea>

<?php
    }

    public function saveMetaBox(
        int $postId
    ): void {

        if (
            !isset($_POST['agency_crm_proposal_nonce']) ||
            !wp_verify_nonce(
                $_POST['agency_crm_proposal_nonce'],
                'agency_crm_proposal_nonce'
            )
        ) {
            return;
        }

        if (
            defined('DOING_AUTOSAVE')
            && DOING_AUTOSAVE
        ) {
            return;
        }

        update_post_meta(
            $postId,
            'status',
            sanitize_text_field(
                $_POST['status'] ?? 'draft'
            )
        );

        update_post_meta(
            $postId,
            'version',
            intval(
                $_POST['version'] ?? 1
            )
        );

        wp_update_post([
            'ID' => $postId,
            'post_content' => wp_kses_post(
                $_POST['proposal_content'] ?? ''
            )
        ]);
    }
}
