<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Gallery extends BaseController
{
    public function index($id_empresa = null, $current_path = '')
    {
        helper(['controlacceso']);

        if (is_null($id_empresa)) {
            $data = usuario_sesion();

            if (!isset($data['id_empresa'])) {
                return redirect()->to('/')->with('error', 'No se pudo determinar la empresa.');
            }

            $id_empresa = $data['id_empresa'];

            $uri = service('uri');
            if ($id_empresa !== $uri->getSegment(2)) {
                return redirect()->to("/gallery/{$id_empresa}");
            }
        }

        $baseDirectory = "/home/u9-ddc4y0armryb/www/dev.showscreen.app/public_html/public/assets/uploads/files/{$id_empresa}";
        $currentDirectory = rtrim($baseDirectory . '/' . $current_path, '/');

        log_message('debug', "Ruta que se está buscando: {$currentDirectory}");

        if (!is_readable($currentDirectory)) {
            log_message('error', "No se puede leer el directorio: {$currentDirectory}");
            return redirect()->to('/')->with('error', 'Ocurrió un problema al intentar acceder a los archivos.');
        }

        $folders = [];
        $images = [];

        // Escanear el directorio actual
        $files = array_diff(scandir($currentDirectory), ['.', '..']);
        foreach ($files as $file) {
            $filePath = $currentDirectory . DIRECTORY_SEPARATOR . $file;
        
            // Detectar carpetas
            if (is_dir($filePath)) {
                $relativeFolderPath = $current_path ? $current_path . '/' . $file : $file;
                $folders[] = $relativeFolderPath;
        
                // Escanear subcarpetas
                $subFiles = array_diff(scandir($filePath), ['.', '..']);
                foreach ($subFiles as $subFile) {
                    $subFilePath = $filePath . DIRECTORY_SEPARATOR . $subFile;
                    if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $subFile)) {
                        $relativePath = str_replace("/home/u9-ddc4y0armryb/www/dev.showscreen.app/public_html/public/", '', $subFilePath);
                        $images[] = base_url('public/' . ltrim($relativePath, '/')); // Cambio aquí
                    }
                }
            }
            // Detectar imágenes en el directorio actual
            elseif (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file)) {
                $relativePath = str_replace("/home/u9-ddc4y0armryb/www/dev.showscreen.app/public_html/public/", '', $filePath);
                $images[] = base_url('public/' . ltrim($relativePath, '/')); // Cambio aquí
            }
        }
        

        log_message('debug', 'Carpetas detectadas: ' . print_r($folders, true));
        log_message('debug', 'Rutas de imágenes generadas: ' . print_r($images, true));

        return view('gallery', [
            'id_empresa' => $id_empresa,
            'current_path' => $current_path,
            'folders' => $folders,
            'images' => $images,
        ]);
    }
}
