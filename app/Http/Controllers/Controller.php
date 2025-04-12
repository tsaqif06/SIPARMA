<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Controller dasar untuk aplikasi yang menyediakan fungsionalitas umum
 * seperti otorisasi dan validasi di seluruh controller.
 *
 * Semua controller yang meng-extend kelas ini akan mewarisi kemampuan
 * untuk melakukan otorisasi dan validasi secara otomatis.
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
