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

        if (!is_readable($currentDirectory)) {
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
                // Verificar si el nombre de la carpeta es un número
                if (is_numeric($file)) {
                    // Escanear imágenes dentro de la carpeta numérica y añadirlas directamente
                    $subFiles = array_diff(scandir($filePath), ['.', '..']);
                    foreach ($subFiles as $subFile) {
                        if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $subFile)) {
                            $subFilePath = $filePath . DIRECTORY_SEPARATOR . $subFile;
                            $relativePath = str_replace("/home/u9-ddc4y0armryb/www/dev.showscreen.app/public_html/public", '', $subFilePath);
                            $images[] = [
                                'url' => base_url('public/' . ltrim($relativePath, '/')),
                                'name' => pathinfo($subFile, PATHINFO_FILENAME) // Extraer el nombre del archivo sin extensión
                            ];
                        }
                    }
                } else {
                    // Agregar carpetas normales a la lista de carpetas navegables
                    $relativeFolderPath = $current_path ? $current_path . '/' . $file : $file;
                    $folders[] = $relativeFolderPath;
                }
            }
            // Detectar imágenes en el directorio actual
            elseif (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file)) {
                $relativePath = str_replace("/home/u9-ddc4y0armryb/www/dev.showscreen.app/public_html/public", '', $filePath);
                $images[] = [
                    'url' => base_url('public/' . ltrim($relativePath, '/')),
                    'name' => pathinfo($file, PATHINFO_FILENAME) // Extraer el nombre del archivo sin extensión
                ];
            }
        }

        return view('gallery', [
            'id_empresa' => $id_empresa,
            'current_path' => $current_path,
            'folders' => $folders,
            'images' => $images,
            'current_folder' => $current_path ? basename($current_path) : 'Raíz' // Extraer la carpeta principal
        ]);
    }
}
