<?php

namespace App\Modules\Dashboard\Controllers;

use App\Modules\Dashboard\Models\DashboardModel;

class DashboardController
{
    protected $model;

    public function __construct()
    {
        $this->model = new DashboardModel();
    }

    public function index()
    {
        $data = $this->model->getDashboardData();
        return view('dashboard.index', ['data' => $data]);
    }
}
