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
    }

    public function getNewLeads(WP_REST_Request $request) : WP_REST_Response {

        $leads = $this->repository->findByStage('new');

        return new WP_REST_Response(
            $leads,
            200
        );
    }
}