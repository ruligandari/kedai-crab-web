<?php

namespace App\Controllers\Chef;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'collapsed' => '',
        ];

        return view('chef/dashboard/index', $data);
    }
}
