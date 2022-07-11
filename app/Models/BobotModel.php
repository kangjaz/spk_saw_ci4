<?php

namespace App\Models;

use CodeIgniter\Model;

class BobotModel extends Model
{
    protected $table                = 'tbl_bobot';
    protected $primaryKey           = '';
    protected $allowedFields        = ['id_kriteria', 'id_sub_kriteria'];

    function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    public function deleteAll()
    {
        return $this->db->table($this->table)
            ->emptyTable();
    }

    public function cekBobot()
    {
        return $this->db->table($this->table)
            ->select('*')
            ->countAllResults();
    }

    public function multiSave($data = array())
    {
        return $this->db->table($this->table)
            ->insertBatch($data);
    }
}
