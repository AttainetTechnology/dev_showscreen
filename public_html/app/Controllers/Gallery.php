<?php

namespace App\Controllers;

class Gallery extends BaseController
{
    public function index($current_path = '', $custom_name = null)
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

        // Añadir las migas de pan
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Galería', base_url('/gallery'));

        // Si hay un nombre personalizado, lo usamos, de lo contrario usamos el nombre de la carpeta.
        $folderName = $custom_name ? $custom_name : basename($current_path);
        $this->addBreadcrumb($this->formatFolderName($folderName)); // Usamos la función de formato

        // Obtener las migas de pan
        $data['amiga'] = $this->getBreadcrumbs();

        // Obtener los datos de carpetas e imágenes
        [$folders, $images] = $this->scanDirectory($currentDirectory, $current_path);

        // Formateamos los nombres de las carpetas
        $formattedFolders = array_map([$this, 'formatFolderName'], $folders);

        return view('gallery', [
            'id_empresa' => $id_empresa,
            'current_path' => $current_path,
            'folders' => $formattedFolders, // Pasar los nombres formateados
            'images' => $images,
            'current_folder' => $current_path ? basename($current_path) : 'Raíz',
            'amiga' => $data['amiga'] // Pasar las migas de pan a la vista
        ]);
    }

    private function formatFolderName($folderName)
    {
        // Reemplaza guiones bajos por espacios
        $formattedName = str_replace('_', ' ', $folderName);

        // Convierte la primera letra de cada palabra a mayúscula
        $formattedName = ucwords($formattedName);

        return $formattedName;
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
                if ($nivelAcceso >= 8 || strpos($file, "_IDUser{$userSesionId}") !== false) {
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
                    if ($nivelAcceso  >= 8|| strpos($subFile, "_IDUser{$userSesionId}") !== false) {
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
        $recordId = $this->request->getPost('record_id');

        // Decodificar la URL para manejar caracteres especiales
        $decodedImageUrl = urldecode($imageUrl);

        // Convertir la URL en una ruta de archivo absoluta
        $basePublicPath = "/home/u9-ddc4y0armryb/www/dev.showscreen.app/public_html/public";
        $filePath = str_replace(base_url('public'), $basePublicPath, $decodedImageUrl);

        // Verificar si el archivo existe
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'El archivo no existe o ya fue eliminado.');
        }

        // Conectar a la base de datos
        $db = \Config\Database::connect();

        // Actualizar las tablas para eliminar referencias a la imagen
        $this->removeImageReferences($db, $decodedImageUrl);

        // Intentar eliminar el archivo
        if (unlink($filePath)) {
            // Obtener la carpeta que contiene la imagen
            $folderPath = dirname($filePath);

            // Verificar si la carpeta está vacía
            if (is_dir($folderPath) && count(array_diff(scandir($folderPath), ['.', '..'])) === 0) {
                rmdir($folderPath);
            }
            return redirect()->back()->with('success', 'Imagen eliminada exitosamente de todas las referencias.');
        } else {
            return redirect()->back()->with('error', 'Ocurrió un error al intentar eliminar la imagen.');
        }
    }


    private function removeImageReferences($db, $imageUrl)
    {
        $mainDb = \Config\Database::connect();

        $data = usuario_sesion();
        if (!isset($data['new_db'])) {
            throw new \RuntimeException('No se pudo determinar la base de datos.');
        }

        $dynamicDb = db_connect($data['new_db']);

        // Obtener el nombre del archivo únicamente
        $fileName = basename($imageUrl);

        // Normalizar el nombre del archivo eliminando prefijos como `numero/` o subdirectorios
        $fileNameNormalized = preg_replace('/^\d+\/|^.*\//', '', $fileName);

        $tablesToUpdate = [
            'productos_necesidad' => ['imagen', $dynamicDb],
            'productos' => ['imagen', $dynamicDb],
            'users' => ['userfoto', $dynamicDb],
            'dbconnections' => ['logo_empresa', 'favicon', 'logo_fichajes', $mainDb],
        ];

        foreach ($tablesToUpdate as $table => $columns) {
            $dbConnection = array_pop($columns);

            foreach ($columns as $column) {
                try {
                    // Ejecutar la consulta con una búsqueda flexible
                    $dbConnection->query("
                    UPDATE {$table}
                    SET {$column} = NULL
                    WHERE {$column} LIKE CONCAT('%', ?)
                ", [$fileNameNormalized]);

                } catch (\Exception $e) {
                    // Manejo silencioso de errores o agregar manejo específico aquí si es necesario
                }
            }
        }
    }




}
