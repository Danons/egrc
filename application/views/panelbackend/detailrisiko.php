<?php
//var_dump($row);die;
echo "<div class='row col-md-12 col-sm-12'>";
echo "<div class='col-md-6 col-lg-6' style='text-align:right'>Nomor Identifikasi :</div>";
echo "<div class='col-md-6 col-lg-6'><strong>" . $row['kode_risiko'] . "</strong></div>";
echo "</div>";

echo "<div class='row col-md-12 col-sm-12'>";
echo "<div class='col-md-6 col-lg-6' style='text-align:right'>Perpektif :</div>";
echo "<div class='col-md-6 col-lg-6'>" . $row['namakpi']. "</div>";
echo "</div>";

echo "<div class='row col-md-12 col-sm-12'>";
echo "<div class='col-md-6 col-lg-6' style='text-align:right'>Nama Risiko :</div>";
echo "<div class='col-md-6 col-lg-6'>" . $row['risiko'] . "</div>";
echo "</div>";

echo "<div class='row col-md-12 col-sm-12'>";
echo "<div class='col-md-6 col-lg-6' style='text-align:right'>Sumber :</div>";
echo "<div class='col-md-6 col-lg-6'>" . $row['sumber'] . "</div>";
echo "</div>";

echo "<div class='row col-md-12 col-sm-12'>";
echo "<div class='col-md-6 col-lg-6' style='text-align:right'>Penyebab :</div>";
echo "<div class='col-md-6 col-lg-6'>" . $row['penyebab'] . "</div>";
echo "</div>";

echo "<div class='row col-md-12 col-sm-12'>";
echo "<div class='col-md-6 col-lg-6' style='text-align:right'>Akibat :</div>";
echo "<div class='col-md-6 col-lg-6'>" . $row['dampak'] . "</div>";
echo "</div>";

echo "<div class='row col-md-12 col-sm-12'>";
echo "<div class='col-md-6 col-lg-6' style='text-align:right'>Sasaran Kerja :</div>";
echo "<div class='col-md-6 col-lg-6'>" . $row['sasaran'] . "</div>";
echo "</div>";

echo "<div class='row col-md-12 col-sm-12'>";
echo "<div class='col-md-6 col-lg-6' style='text-align:right'>State : </div>";
echo "<div class='col-md-6 col-lg-6'>Residual <br/>" . $row['state'] . "</div>";
echo "</div>";


echo "<div class='row col-md-12 col-sm-12'>";
echo "<div class='col-md-6 col-lg-6' style='text-align:right'>Pengendalian Risiko Berjalan : </div>";
echo "<div class='col-md-6 col-lg-6'>" . $row['pengendalian_risiko_berjalan'] . "</div>";
echo "</div>";

echo "<div class='row col-md-12 col-sm-12'>";
echo "<div class='col-md-6 col-lg-6' style='text-align:right'>Perpektif :</div>";
echo "<div class='col-md-6 col-lg-6'>" . $row['target_penyelesaian'] . "</div>";
echo "</div>";

echo "<div class='row col-md-12 col-sm-12'>";
echo "<div class='col-md-6 col-lg-6' style='text-align:right'>Rencana Pengendalian / Rencana Mitigasi :</div>";
echo "<div class='col-md-6 col-lg-6'>" . $row['nama'] . "</div>";
echo "</div>";

echo "<div class='row col-md-12 col-sm-12'>";
echo "<div class='col-md-6 col-lg-6' style='text-align:right'>Penyebab :</div>";
echo "<div class='col-md-6 col-lg-6'>" . $row['penyebab'] . "</div>";
echo "</div>";

echo "<div class='row col-md-12 col-sm-12'>";
echo "<div class='col-md-6 col-lg-6' style='text-align:right'>Akibat :</div>";
echo "<div class='col-md-6 col-lg-6'>" . $row['akibat'] . "</div>";
echo "</div>";

echo "<div class='row col-md-12 col-sm-12'>";
echo "<div class='col-md-6 col-lg-6' style='text-align:right'>Sasaran Kerja :</div>";
echo "<div class='col-md-6 col-lg-6'>" . $row['sasaran'] . "</div>";
echo "</div>";

echo "<div class='row col-md-12 col-sm-12'>";
echo "<div class='col-md-6 col-lg-6' style='text-align:right'>State : </div>";
echo "<div class='col-md-6 col-lg-6'>Residual <br/>" . $row['state'] . "</div>";
echo "</div>";
