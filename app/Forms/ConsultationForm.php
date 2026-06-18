<?php

namespace AgencyCRM\Forms;

class ConsultationForm
{
    public function register(): void
    {
        add_action('admin_post_nopriv_agency_consultation_form', [$this, 'submit']);
        add_action('admin_post_agency_consultation_form', [$this, 'submit']);
    }

    public function submit(): void
    {
        $name    = sanitize_text_field($_POST['name'] ?? '');
        $email   = sanitize_email($_POST['email'] ?? '');
        $phone   = sanitize_text_field($_POST['phone'] ?? '');
        $company = sanitize_text_field($_POST['company'] ?? '');
        $service = sanitize_text_field($_POST['service'] ?? '');
        $budget  = sanitize_text_field($_POST['budget'] ?? '');
        $message = sanitize_textarea_field($_POST['message'] ?? '');

        $leadId = wp_insert_post([
            'post_type'   => 'lead',
            'post_status' => 'publish',
            'post_title'  => $name,
        ]);

        if (!$leadId || is_wp_error($leadId)) {
            error_log('AgencyCRM Error: Failed to create lead post for ' . $email);
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

        // --- N8N WEBHOOK INTEGRATION ---
        $webhook_url = 'http://localhost:5678/webhook-test/a5ed5266-1576-48f2-ae5c-c16c6f37f756';

        $body = [
            'lead_id' => $leadId,
            'name'    => $name,
            'email'   => $email,
            'phone'   => $phone,
            'company' => $company,
            'service' => $service,
            'budget'  => $budget,
            'message' => $message,
            'source'  => 'website',
            'stage'   => 'new',
        ];

        // Build request arguments
        $args = [
            'method'      => 'POST',
            'timeout'     => 15,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking'    => true,
            'headers'     => [
                'Content-Type' => 'application/json; charset=utf-8',
            ],
            'body'        => json_encode($body),
            'data_format' => 'body',
        ];

        // Add Basic Auth header if credentials are defined
        if (defined('N8N_WEBHOOK_USER') && defined('N8N_WEBHOOK_PASS')) {
            $auth_string = base64_encode(N8N_WEBHOOK_USER . ':' . N8N_WEBHOOK_PASS);
            $args['headers']['Authorization'] = 'Basic ' . $auth_string;
        }

        error_log("AgencyCRM Info: Attempting to send Lead ID {$leadId} to n8n.");

        $response = wp_remote_post($webhook_url, $args);

        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            error_log("AgencyCRM Error: Webhook failed for Lead ID {$leadId}. Reason: {$error_message}");
        } else {
            $response_code = wp_remote_retrieve_response_code($response);
            if ($response_code >= 200 && $response_code < 300) {
                error_log("AgencyCRM Success: Lead ID {$leadId} successfully sent to n8n. Response code: {$response_code}");
            } else {
                $response_body = wp_remote_retrieve_body($response);
                error_log("AgencyCRM Warning: n8n responded with code {$response_code} for Lead ID {$leadId}. Response: {$response_body}");
            }
        }
        // --- END N8N WEBHOOK INTEGRATION ---

        wp_redirect(home_url('/free-consultation?success=1'));
        exit;
    }
}