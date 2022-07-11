<?php

namespace App\Models;

use CodeIgniter\Model;

class PenilaianModel extends Model
{
    protected $table                = 'tbl_penilaian';
    protected $primaryKey           = '';
    protected $allowedFields        = ['id_alternative', 'id_kriteria', 'id_sub_kriteria'];

    function __construct()
    {
        parent::__construct();
        $this->db = db_connect();
    }

    public function cekAlternative($id_alternative = null)
    {
        return $this->db->table($this->table)
            ->where('id_alternative', $id_alternative)
            ->countAllResults();
    }

    public function multiSave($data = array())
    {
        return $this->db->table($this->table)
            ->insertBatch($data);
    }

    public function getPenilaian()
    {
        return $this->db->table($this->table)
            ->select('nama_alternative, tbl_alternative.id_alternative AS id_alternative')
            ->join('tbl_alternative', 'tbl_alternative.id_alternative = tbl_penilaian.id_alternative')
            ->groupBy('nama_alternative, tbl_alternative.id_alternative')
            ->orderBy('nama_alternative', 'ASC')
            ->get();
    }

    public function getPenilaianAlternative($id_alternative = null)
    {
        return $this->db->table($this->table)
            ->select('nama_alternative, judul_kriteria, keterangan')
            ->join('tbl_alternative', 'tbl_alternative.id_alternative = tbl_penilaian.id_alternative')
            ->join('tbl_kriteria', 'tbl_kriteria.id_kriteria = tbl_penilaian.id_kriteria')
            ->join('tbl_sub_kriteria', 'tbl_sub_kriteria.id_sub_kriteria = tbl_penilaian.id_sub_kriteria')
            ->where('tbl_penilaian.id_alternative', $id_alternative)
            ->get()
            ->getResultArray();
    }

    public function deletePenilaian($id_alternative = null)
    {
        return $this->db->table($this->table)
            ->where('id_alternative', $id_alternative)
            ->delete();
    }

    public function getNilai($id_alternative = null)
    {
        return $this->db->table('tbl_kriteria')
            ->select('tbl_kriteria.id_kriteria AS id_kriteria, b.nilai AS nilai, b.keterangan AS keterangan')
            ->join('tbl_sub_kriteria AS a', 'a.id_kriteria = tbl_kriteria.id_kriteria')
            ->join('tbl_bobot', 'tbl_bobot.id_kriteria = tbl_kriteria.id_kriteria')
            ->join('(SELECT nilai, keterangan, tbl_penilaian.id_kriteria AS id_kriteria FROM tbl_penilaian JOIN tbl_sub_kriteria ON(tbl_penilaian.id_sub_kriteria = tbl_sub_kriteria.id_sub_kriteria) WHERE id_alternative = "' . $id_alternative . '") AS b', 'b.id_kriteria = tbl_kriteria.id_kriteria', 'left')
            ->groupBy('tbl_kriteria.id_kriteria')
            ->orderBy('tbl_kriteria.id_kriteria', 'ASC')
            ->get();
    }

    public function getNilaiPenilaian($id_kriteria = null)
    {
        return $this->db->table($this->table)
            ->select('max(nilai) AS nilai_max, min(nilai) AS nilai_min, sifat')
            ->join('tbl_kriteria', 'tbl_kriteria.id_kriteria = tbl_penilaian.id_kriteria')
            ->join('tbl_sub_kriteria', 'tbl_sub_kriteria.id_sub_kriteria = tbl_penilaian.id_sub_kriteria')
            ->where('tbl_penilaian.id_kriteria', $id_kriteria)
            ->groupBy('tbl_penilaian.id_kriteria, sifat')
            ->get();
    }

    public function cekPenilaian()
    {
        return $this->db->table($this->table)
            ->select('*')
            ->countAllResults();
    }
}
