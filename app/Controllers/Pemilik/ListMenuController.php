<?php

namespace App\Controllers\Pemilik;

use App\Controllers\BaseController;

class ListMenuController extends BaseController
{
    public function index()
    {
        $listMenu = new \App\Models\MakananModel();
        $menus = $listMenu->findAll();
        $data = [
            'title' => 'List Menu',
            'listmenu' => $menus,

        ];

        return view('pemilik/list-menu/index', $data);
    }
}
