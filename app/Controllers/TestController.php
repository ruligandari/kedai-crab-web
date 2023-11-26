<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use function App\Helpers\enkripsi_base64;
use function App\Helpers\dekripsi_base64;

class TestController extends BaseController
{
    public function index()
    {
        // panggil base64_helper
        helper('base64');

        // enkripsi
        $enkripsi = enkripsi_base64('CB0112122023');
        echo 'Enkripsi :' . $enkripsi . '<br>';
        echo 'Dekripsi :' . base64_decode($enkripsi) . '<br>';
    }
}
