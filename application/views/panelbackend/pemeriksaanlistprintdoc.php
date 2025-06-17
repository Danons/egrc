<center>
    <h1 style="font-size: 20px;">BAB III URAIAN HASIL PEMERIKSAAN</h1>
</center>
<?php
$str = "<ol type='A' class='listno'>";
foreach ($this->data['rows'] as $id_pemeriksaan => $rs) {
    $rw = $this->model->GetByPk($id_pemeriksaan);
    $str .= "<li>";
    $str .= "<b>" . $this->data['unitarr'][$rw['id_unit']] . "</b>";
    foreach ($rs as $id_bidang_pemeriksaan => $rss) {
        $str .= "<br/><b>" . strtoupper($this->data['bidangpemeriksaanarr'][$id_bidang_pemeriksaan]) . "</b>";
        foreach ($rss as $r) {
            $str .= "<br/><br/>" . $r['judul_temuan'];
            $str .= "<br/><br/><b>Kondisi</b>" . $r['kondisi'];
            $str .= "<br/><b>Kriteria</b>" . $r['kriteria'];
            $str .= "<br/><b>Sebab</b>" . $r['sebab'];
            $str .= "<br/><b>Akibat</b>" . $r['akibat'];
            $str .= "<br/><b>Rekomendasi</b>" . $r['rekomendasi'];
            $tanggapan = implode(",", $this->conn->GetList("select 
				id_pemeriksaan_temuan_diskusi as idkey, 
				keterangan as val 
				from pemeriksaan_temuan_diskusi 
				where keterangan is not null and keterangan <> '' and id_pemeriksaan_temuan = " . $this->conn->escape($r['id_pemeriksaan_temuan'])));
            $str .= "<br/><b>Tanggapan Penanggung Jawab Objek Pemeriksaan</b><br/>" . $tanggapan;
            $kesimpulan = implode(",", $this->conn->GetList("select 
				id_pemeriksaan_tindak_lanjut as idkey, 
				kesimpulan as val 
				from pemeriksaan_tindak_lanjut 
				where kesimpulan is not null and kesimpulan <> '' and id_pemeriksaan_temuan = " . $this->conn->escape($r['id_pemeriksaan_temuan'])));
            $str .= "<br/><b>Kesimpulan</b>" . $kesimpulan;
        }
    }
    $str .= "<br/><br/></li>";
}
$str .= "</ol>";

echo $str;
?>
<style>
    .listno>li::marker {
        font-weight: bold;
    }
</style>