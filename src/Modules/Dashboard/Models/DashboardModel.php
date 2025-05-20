<?php

namespace App\Modules\Dashboard\Models;

use PDO;
use App\Core\Database;

class DashboardModel
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getDashboardData(): array
    {
        return [
            'user_count' => 42,
            'active_users' => 35,
            'inactive_users' => 7
        ];
    }
}
