<?php

namespace App\Models;

use CodeIgniter\Model;

class SubKriteriaModel extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tbl_sub_kriteria';
    protected $primaryKey           = 'id_sub_kriteria';
    protected $allowedFields        = ['id_kriteria', 'nilai', 'keterangan'];

    protected $column_order = array(null, 'judul_kriteria', 'nilai', 'keterangan', null);
    protected $column_search = array('judul_kriteria', 'nilai', 'keterangan');
    protected $order = array('judul_kriteria' => 'asc');
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
        $this->dt->join('tbl_kriteria', 'tbl_kriteria.id_kriteria = tbl_sub_kriteria.id_kriteria');

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

    public function getSubkriteria($id_kriteria = null)
    {
        return $this->dt->select(
            'keterangan,
            tbl_sub_kriteria.id_sub_kriteria AS id_sub_kriteria,
            nilai,
            tbl_bobot.id_sub_kriteria AS id_sub'
        )
            ->join('tbl_bobot', 'tbl_bobot.id_sub_kriteria = tbl_sub_kriteria.id_sub_kriteria', 'left')
            ->where('tbl_sub_kriteria.id_kriteria', $id_kriteria)
            ->get();
    }

    public function getSubKriteriaPenilaian($id_kriteria = null)
    {
        return $this->dt->select('keterangan, id_sub_kriteria, nilai')
            ->where('id_kriteria', $id_kriteria)
            ->get();
    }

    public function getSubkriteriaAlternative($id_kriteria = null, $id_alternative = null)
    {
        return $this->dt->select(
            'keterangan,
            tbl_sub_kriteria.id_sub_kriteria AS id_sub_kriteria,
            nilai,
            tbl_penilaian.id_sub_kriteria AS id_sub'
        )
            ->join('tbl_penilaian', 'tbl_penilaian.id_sub_kriteria = tbl_sub_kriteria.id_sub_kriteria AND id_alternative = "' . $id_alternative . '"', 'left')
            ->where('tbl_sub_kriteria.id_kriteria', $id_kriteria)
            ->get();
    }
}
