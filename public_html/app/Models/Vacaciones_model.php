<?Php 
namespace App\Models;

use CodeIgniter\Model;

class Vacaciones_model extends Model{
    protected $table = 'vacaciones';
    protected $primaryKey='id';
    protected $allowedFields= ['id','user_id','desde','hasta','observaciones'];
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}