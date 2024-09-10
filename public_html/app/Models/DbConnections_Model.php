<?php
namespace App\Models;

use CodeIgniter\Model;

class DbConnections_Model extends Model
{
    protected $table = 'dbconnections';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['db_name', 'db_user', 'db_password', 'nombre_empresa', 'logo_empresa', 'favicon', 'logo_fichajes', 'NIF'];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}
