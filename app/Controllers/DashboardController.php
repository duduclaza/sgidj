<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Improvement;
use App\Models\Notification;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(): void
    {
        $stats = (new Improvement())->dashboardStats();
        $users = (new User())->list();
        $unread = 0;
        if ($id = \App\Core\Auth::id()) {
            $unread = (new Notification())->unreadCount($id);
        }

        $this->view('dashboard/index', [
            'title' => 'Dashboard',
            'stats' => $stats,
            'users' => $users,
            'unread' => $unread,
        ]);
    }
}
