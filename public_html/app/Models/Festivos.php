<?Php 
namespace App\Models;

use CodeIgniter\Model;

class Festivos extends Model{
    protected $table = 'festivos';
    protected $primaryKey='id';
    protected $allowedFields= ['id','festivo','fecha','tipo_festivo'];
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}