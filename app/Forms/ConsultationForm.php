<?php

namespace AgencyCRM\Forms;

class ConsultationForm
{
    public function register(): void
    {
        add_action(
            'admin_post_nopriv_agency_consultation_form',
            [$this, 'submit']
        );

        add_action(
            'admin_post_agency_consultation_form',
            [$this, 'submit']
        );
    }

    public function submit(): void
    {
        $name = sanitize_text_field($_POST['name'] ?? '');
        $email = sanitize_email($_POST['email'] ?? '');
        $phone = sanitize_text_field($_POST['phone'] ?? '');
        $company = sanitize_text_field($_POST['company'] ?? '');
        $service = sanitize_text_field($_POST['service'] ?? '');
        $budget = sanitize_text_field($_POST['budget'] ?? '');
        $message = sanitize_textarea_field($_POST['message'] ?? '');

        $leadId = wp_insert_post([
            'post_type'   => 'lead',
            'post_status' => 'publish',
            'post_title'  => $name,
        ]);

        if (!$leadId) {
            wp_die('Unable to create lead.');
        }

        update_post_meta($leadId, 'contact_name', $name);
        update_post_meta($leadId, 'email', $email);
        update_post_meta($leadId, 'phone', $phone);
        update_post_meta($leadId, 'company', $company);
        update_post_meta($leadId, 'service', $service);
        update_post_meta($leadId, 'budget', $budget);
        update_post_meta($leadId, 'message', $message);

        update_post_meta($leadId, 'source', 'website');
        update_post_meta($leadId, 'stage', 'new');

        wp_redirect(home_url('/free-consultation?success=1'));
        exit;
    }
}