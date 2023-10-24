<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
         // Memeriksa apakah pengguna sedang login
         if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Mendapatkan peran pengguna saat ini
        $role = session()->get('role');

        // Mendapatkan segment pertama dari URI
        $uri = service('uri');
        $segment = $uri->getSegment(1);

        // Cek peran pengguna dan jalankan aksi sesuai dengan segment URI
        switch ($role) {
            case "1":
                if ($segment !== 'pemilik') {
                    return redirect()->to('/pemilik/dashboard');
                }
                break;
            case "2":
                if ($segment !== 'kasir') {
                    return redirect()->to('/kasir/dashboard');
                }
                break;
            case "3":
                if ($segment !== 'chef') {
                    return redirect()->to('/chef/dashboard');
                }
                break;
            case "4":
                if ($segment !== 'kurir') {
                    return redirect()->to('/kurir/dashboard');
                }
                break;
            default:
                // Tindakan jika peran tidak valid
                return redirect()->to('/login');
                break;
        }

    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
