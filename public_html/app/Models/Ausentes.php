<?Php
namespace App\Models;

use CodeIgniter\Model;

class ausentes extends Model
{
	protected $table = 'users';
	protected $primaryKey = 'id';
	protected $allowedFields = ['id', 'nombre_usuario', 'apellidos_usuario', 'user_ficha', 'user_activo'];
	protected $validationRules = [];
	protected $validationMessages = [];
	protected $skipValidation = false;
}
