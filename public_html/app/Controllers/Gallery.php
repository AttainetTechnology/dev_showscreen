<?php

namespace App\Controllers;

class Gallery extends BaseController
{
    public function index($current_path = '')
    {
        helper(['controlacceso']);

        $id_empresa = $this->getIdEmpresa();
        if (!$id_empresa) {
            return redirect()->to('/')->with('error', 'No se pudo determinar la empresa.');
        }

        $currentDirectory = $this->buildDirectoryPath($current_path);
        if (!is_readable($currentDirectory)) {
            return redirect()->to('/')->with('error', 'Ocurrió un problema al intentar acceder a los archivos.');
        }

        [$folders, $images] = $this->scanDirectory($currentDirectory, $current_path);

        return view('gallery', [
            'id_empresa' => $id_empresa,
            'current_path' => $current_path,
            'folders' => $folders,
            'images' => $images,
            'current_folder' => $current_path ? basename($current_path) : 'Raíz',
        ]);
    }

    private function getIdEmpresa()
    {
        $data = usuario_sesion();

        if (!isset($data['id_empresa'])) {
            return null;
        }

        return $data['id_empresa'];
    }

    private function buildDirectoryPath($current_path)
    {
        $id_empresa = $this->getIdEmpresa(); // Obtén el id_empresa directamente
        $baseDirectory = "/home/u9-ddc4y0armryb/www/dev.showscreen.app/public_html/public/assets/uploads/files/{$id_empresa}";
        return rtrim($baseDirectory . '/' . $current_path, '/');
    }

    private function scanDirectory($currentDirectory, $current_path)
    {
        $data = usuario_sesion();
        $userSesionId = isset($data['id_user']) ? $data['id_user'] : null; // ID del usuario autenticado
        $nivelAcceso = isset($data['nivel']) ? $data['nivel'] : 0; // Nivel de acceso del usuario

        $folders = [];
        $images = [];
        $publicPathPrefix = "/home/u9-ddc4y0armryb/www/dev.showscreen.app/public_html/public";

        $files = array_diff(scandir($currentDirectory), ['.', '..']);

        foreach ($files as $file) {
            $filePath = $currentDirectory . DIRECTORY_SEPARATOR . $file;

            if (is_dir($filePath)) {
                $this->processDirectory($file, $filePath, $current_path, $folders, $images, $publicPathPrefix, $userSesionId, $nivelAcceso);
            } elseif ($this->isImage($file)) {
                // Si el nivel de acceso no es 9, filtrar las imágenes por ID de usuario
                if ($nivelAcceso == 9 || strpos($file, "_IDUser{$userSesionId}") !== false) {
                    $images[] = $this->buildImageData($filePath, $publicPathPrefix);
                }
            }
        }

        return [$folders, $images];
    }

    private function processDirectory($file, $filePath, $current_path, &$folders, &$images, $publicPathPrefix, $userSesionId, $nivelAcceso)
    {
        if (is_numeric($file)) {
            $subFiles = array_diff(scandir($filePath), ['.', '..']);

            foreach ($subFiles as $subFile) {
                if ($this->isImage($subFile)) {
                    // Si el nivel de acceso no es 9, filtrar las imágenes por ID de usuario
                    if ($nivelAcceso == 9 || strpos($subFile, "_IDUser{$userSesionId}") !== false) {
                        $subFilePath = $filePath . DIRECTORY_SEPARATOR . $subFile;
                        $images[] = $this->buildImageData($subFilePath, $publicPathPrefix);
                    }
                }
            }
        } else {
            $relativeFolderPath = $current_path ? $current_path . '/' . $file : $file;
            $folders[] = $relativeFolderPath;
        }
    }


    private function isImage($file)
    {
        return preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file);
    }

    private function buildImageData($filePath, $publicPathPrefix)
    {
        $relativePath = str_replace($publicPathPrefix, '', $filePath);
        return [
            'url' => base_url('public/' . ltrim($relativePath, '/')),
            'name' => pathinfo($filePath, PATHINFO_FILENAME),
        ];
    }
    public function delete()
    {
        helper(['filesystem', 'security']);

        // Obtener datos del formulario
        $imageUrl = $this->request->getPost('image_path');
        $recordId = $this->request->getPost('record_id'); // Obtener el ID del registro

        // Convertir la URL en una ruta de archivo absoluta
        $basePublicPath = "/home/u9-ddc4y0armryb/www/dev.showscreen.app/public_html/public";
        $filePath = str_replace(base_url('public'), $basePublicPath, $imageUrl);

        // Verificar si el archivo existe
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'El archivo no existe o ya fue eliminado.');
        }

        // Eliminar el registro de la base de datos
        if ($recordId) {
            $db = \Config\Database::connect();
            $builder = $db->table('tabla_correspondiente'); // Ajusta el nombre de la tabla
            $builder->where('id', $recordId)->delete(); // Usa el ID para eliminar el registro
        }

        // Obtener la carpeta que contiene la imagen
        $folderPath = dirname($filePath);

        // Intentar eliminar el archivo
        if (unlink($filePath)) {
            // Verificar si la carpeta está vacía
            if (is_dir($folderPath) && count(array_diff(scandir($folderPath), ['.', '..'])) === 0) {
                // Eliminar la carpeta si está vacía
                rmdir($folderPath);
            }
            return redirect()->back()->with('success', 'Imagen, registro y carpeta eliminados exitosamente, si corresponde.');
        } else {
            return redirect()->back()->with('error', 'Ocurrió un error al intentar eliminar la imagen.');
        }
    }

}
