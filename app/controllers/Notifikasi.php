<?php

class Notifikasi extends Controller {

    public function __construct()
    {
        if(!isset($_SESSION['user'])) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit;
        }
    }

    public function getLatest()
    {
        header('Content-Type: application/json');
        $user_id = $_SESSION['user']['id'];
        
        $unreadCount = $this->model('NotifikasiModel')->getCountUnread($user_id);
        $latest = $this->model('NotifikasiModel')->getUnread($user_id);
        
        echo json_encode([
            'status' => 'success',
            'count' => $unreadCount,
            'data' => $latest
        ]);
        exit;
    }

    public function markRead($id)
    {
        header('Content-Type: application/json');
        $user_id = $_SESSION['user']['id'];
        
        if($this->model('NotifikasiModel')->markAsRead($id, $user_id) > 0) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to mark as read']);
        }
        exit;
    }
}
