<?php

namespace App\Models;

use CodeIgniter\Model;

class KriteriaModel extends Model
{
    protected $table                = 'tbl_kriteria';
    protected $primaryKey           = 'id_kriteria';
    protected $allowedFields        = ['kode_kriteria', 'judul_kriteria', 'sifat'];

    protected $column_order = array(null, 'kode_kriteria', 'judul_kriteria', 'sifat', null);
    protected $column_search = array('kode_kriteria', 'judul_kriteria', 'sifat');
    protected $order = array('kode_kriteria' => 'asc');
    protected $db;
    protected $dt;

    function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
        $this->dt = $this->db->table($this->table);
    }

    private function _get_datatables_query()
    {
        $i = 0;
        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->dt->groupStart();
                    $this->dt->like($item, $_POST['search']['value']);
                } else {
                    $this->dt->orLike($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i)
                    $this->dt->groupEnd();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->dt->orderBy($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->dt->orderBy(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1) {
            $this->dt->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->dt->get();
        return $query->getResult();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->dt->countAllResults();
    }

    public function count_all()
    {
        $tbl_storage = $this->db->table($this->table);
        return $tbl_storage->countAllResults();
    }

    public function getFormKriteria()
    {
        return $this->dt->select('tbl_kriteria.id_kriteria AS id_kriteria, judul_kriteria')
            ->join('tbl_sub_kriteria', 'tbl_sub_kriteria.id_kriteria = tbl_kriteria.id_kriteria')
            ->groupBy('tbl_kriteria.id_kriteria, judul_kriteria')
            ->orderBy('tbl_kriteria.id_kriteria', 'ASC')
            ->get();
    }

    public function getKriteria()
    {
        return $this->dt->select(
            'tbl_kriteria.id_kriteria AS id_kriteria,
            kode_kriteria,
            judul_kriteria,
            nilai'
        )
            ->join('tbl_bobot', 'tbl_bobot.id_kriteria = tbl_kriteria.id_kriteria')
            ->join('tbl_sub_kriteria', 'tbl_sub_kriteria.id_sub_kriteria = tbl_bobot.id_sub_kriteria')
            ->groupBy('tbl_kriteria.id_kriteria, judul_kriteria')
            ->orderBy('tbl_kriteria.id_kriteria', 'ASC')
            ->get();
    }
}
