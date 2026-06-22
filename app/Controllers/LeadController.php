<?php

namespace AgencyCRM\Controllers;

use AgencyCRM\Repositories\LeadRepository;
use WP_REST_Request;
use WP_REST_Response;

class LeadController
{
    private LeadRepository $repository;

    public function __construct()
    {
        $this->repository = new LeadRepository();
    }

    public function register(): void
    {
        add_action(
            'rest_api_init',
            [$this, 'registerRoutes']
        );
    }

    public function registerRoutes(): void
    {
        register_rest_route(
            'agency-crm/v1',
            '/leads/new',
            [
                'methods' => 'GET',
                'callback' => [$this, 'getNewLeads'],
                'permission_callback' => '__return_true',
            ]
        );

        register_rest_route(
            'agency-crm/v1',
            '/leads',
            [
                'methods' => 'POST',
                'callback' => [$this, 'createLead'],
                'permission_callback' => '__return_true',
            ]
        );

        register_rest_route(
            'agency-crm/v1',
            '/leads/discovery-ready',
            [
                'methods' => 'GET',
                'callback' => [$this, 'getLeadsWithDiscoveryNotes'],
                'permission_callback' => '__return_true',
            ]
        );
    }

    public function getNewLeads(WP_REST_Request $request): WP_REST_Response
    {

        $leads = $this->repository->findByStage('new');

        return new WP_REST_Response(
            $leads,
            200
        );
    }

    public function createLead(WP_REST_Request $request): WP_REST_Response
    {
        $data = $request->get_json_params();

        $leadId = $this->repository->create($data);

        return new WP_REST_Response([
            'message' => 'Lead Created successfully',
            'lead_id' => $leadId
        ], 200);
    }

    public function getLeadsWithDiscoveryNotes(WP_REST_Request $request): WP_REST_Response
    {
        $leadsWithDiscovery = $this->repository->findLeadsWithCallNotes();
        return new WP_REST_Response($leadsWithDiscovery, 200);
    }
}
