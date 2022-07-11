<?php

namespace App\Models;

use CodeIgniter\Model;

class HasilModel extends Model
{
    protected $table                = 'tbl_hasil';
    protected $primaryKey           = '';
    protected $allowedFields        = ['id_alternative', 'hasil'];

    function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    public function clearTable()
    {
        return $this->db->table($this->table)
            ->emptyTable();
    }

    public function multiSave($data = array())
    {
        return $this->db->table($this->table)
            ->insertBatch($data);
    }

    public function getWinner()
    {
        return $this->db->table($this->table)
            ->join('tbl_alternative', 'tbl_alternative.id_alternative = tbl_hasil.id_alternative')
            ->select('nama_alternative, hasil')
            ->where('hasil', "(SELECT max(hasil) FROM tbl_hasil)", false)
            ->get();
    }
}
