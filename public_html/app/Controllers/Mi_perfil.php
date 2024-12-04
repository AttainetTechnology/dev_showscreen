<?php
namespace App\Controllers;
use \Gumlet\ImageResize;

class Mi_perfil extends BaseController
{ 
    public function index($id = null)
    {
        return $this->edit($id);
    }

    public function save()
    {
        $session = session();
        $userId = $this->request->getPost('id');

        $post_array = $this->request->getPost();
        $updateDataOriginal = ['email' => $post_array['email']];
        foreach (['username'] as $field) {
            if (!empty($post_array[$field])) {
                $updateDataOriginal[$field] = $post_array[$field];
            }
        }

        if (!empty($post_array['password'])) {
            if ($this->isValidPassword($post_array['password'])) {
                $updateDataOriginal['password'] = md5($post_array['password']);
            } else {
                $session->setFlashdata('error', 'La contraseña no cumple con los requisitos de seguridad.');
                return redirect()->back()->withInput();
            }
        }

        $updateDataCliente = ['email' => $post_array['email']];

        $dbOriginal = db_connect();
        if (!$dbOriginal) {
            $session->setFlashdata('error', 'Error en la conexión a la base de datos.');
            return redirect()->back()->withInput();
        }

        // Verificar si el nombre de usuario ya existe
        $existingUser = $dbOriginal->table('users')
                                   ->where('username', $post_array['username'])
                                   ->where('id !=', $userId)
                                   ->get()
                                   ->getRow();

        if ($existingUser) {
            $session->setFlashdata('error', 'El nombre de usuario ya existe.');
            return redirect()->back()->withInput();
        }

        $imageFile = $this->request->getFile('userfoto');
        $maxSize = 1024 * 1024; // 1MB
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        $id_empresa = $this->data['id_empresa'];
        $specificPath = FCPATH . "public/assets/uploads/files/" . $id_empresa . "/usuarios/" . $userId . "/";

        if ($this->request->getPost('deleteImage')) {
            array_map('unlink', glob($specificPath . "*"));
            $updateDataCliente['userfoto'] = null;
        }

        if ($imageFile->isValid() && !$imageFile->hasMoved()) {
            if ($imageFile->getSize() > $maxSize) {
                $session->setFlashdata('error', 'El tamaño de la imagen excede el máximo permitido de 1MB.');
                return redirect()->back()->withInput();
            }

            if (!in_array($imageFile->getMimeType(), $allowedTypes)) {
                $session->setFlashdata('error', 'El formato de la imagen no está permitido. Los formatos permitidos son JPEG, PNG y GIF.');
                return redirect()->back()->withInput();
            }

            if (!is_dir($specificPath)) {
                mkdir($specificPath, 0777, true);
            } else {
                array_map('unlink', glob($specificPath . "*"));
            }

            $imageFile->move($specificPath);
            $imagePath = $specificPath . $imageFile->getName();
            $this->compressAndResizeImage($imagePath, 400, 200);

            $updateDataCliente['userfoto'] = $userId . '/' . $imageFile->getName();
        }

        if (!empty($updateDataOriginal)) {
            $dbOriginal->table('users')->where('id', $userId)->update($updateDataOriginal);
        }

        $database = datos_user();
        $dbCliente = db_connect($database['new_db']);
        if (!empty($updateDataCliente)) {
            $dbCliente->table('users')->where('id', $userId)->update($updateDataCliente);
        }

        $this->logAction('Usuario Planta', 'Edita Datos Acceso', $post_array);
        return redirect()->to('/Mi_perfil/index/' . $userId);
    }

    private function isValidPassword($password)
    {
        if (strlen($password) < 8) {
            return false;
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }

        return true;
    }

    private function compressAndResizeImage($imagePath, $width, $height)
    {
        $image = new \Gumlet\ImageResize($imagePath);
        $image->resizeToBestFit($width, $height);
        $image->save($imagePath);
    }

    public function edit($id = null)
    {  

        
        if ($id === null) {
            return redirect()->to('/');
        }

        $db = db_connect();
        $query = $db->table('users')->where('id', $id)->get();
        $user = $query->getRow();

        if (!$user) {
            return redirect()->to('/');
        }

        $data = datos_user();
        $database = $data['new_db'];
        $dbClient = db_connect($database);

        $queryClient = $dbClient->table('users')->where('id', $id)->get();
        $userClient = $queryClient->getRow();

        $id_empresa = $this->data['id_empresa'];
        $specificPath = "public/assets/uploads/files/" . $id_empresa . "/usuarios/" . $id . "/";
        if (isset($userClient->userfoto)) {
            $slashPosition = strpos($userClient->userfoto, '/');
            $imageNameWithoutPrefix = substr($userClient->userfoto, $slashPosition + 1);
            $user->imagePath = $specificPath . $imageNameWithoutPrefix;
        }
        $this->addBreadcrumb('Inicio', base_url('/'));
        $this->addBreadcrumb('Mi perfil');
        $data['amiga'] = $this->getBreadcrumbs();

        $data['user'] = $user;
        return view('edit_mi_perfil', $data);
    }

    public function getUserData($id)
    {
        $db = db_connect();
        $query = $db->table('users')->where('id', $id)->get();
        $user = $query->getRow();

        if (!$user) {
            return $this->response->setStatusCode(404)->setBody('Usuario no encontrado');
        }

        return $this->response->setJSON($user);
    }
}
?>
