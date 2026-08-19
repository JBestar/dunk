<?php 
namespace App\Controllers;

use App\Models\ConfSite_Model;

class Pages extends BaseController
{
    public function index()
    {
        return $this->response->redirect($_ENV['app.furl'] . '/pages/login');
    }

    public function login()
    {
        // 이미 로그인 상태면 관리자 메인으로 이동
        if (is_login()) {
            return $this->response->redirect($_ENV['app.furl'] . '/');
        }

        $model = new ConfSite_Model();
        $siteName = $model->getSiteName();

        return view('/pages/login', [
            'site_name' => $siteName
        ]);
    }

    public function logout()
    {
        $sess_id = $this->session->session_id ?? '';
        writeLog("[page] logout (" . $sess_id . ")");

        $this->sess_destroy();

        return $this->response->redirect($_ENV['app.furl'] . '/pages/login');
    }

    public function nopermit()
    {
        return $this->response->redirect($_ENV['app.furl'] . '/pages/login');
    }
}