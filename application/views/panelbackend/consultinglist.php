<div class="col-12 rounded p-4" style=" background-color:#f4f5f7;">
    <div class="row gap-3 mb-3">
        <div class="col-5 rounded p-4" style="background-color: #36d7b6;">
            <div class="row text-white">
                <div class="col-4 h4">
                    TOTAL
                </div>
                <div class="col-4 h4">
                    <?= $rows ? count($totalopenmsg) : 0 ?>
                </div>
                <div class="col-4 h4">
                    <?= $rows ? count($totalclosemsg) : 0 ?>
                </div>
            </div>
            <div class="row text-white">
                <div class="col-4 h4">
                    <?= $rows ? count($rows) : 0 ?>
                </div>
                <div class="col-4 " style="font-size: 14px; font-weight:bold;">
                    OPEN
                </div>
                <div class="col-4 " style="font-size: 14px; font-weight:bold;">
                    CLOSE
                </div>
            </div>
            <div class="row ">
                <div class="col-4 text-white" style="font-size: 14px; font-weight:bold;">
                    TANYA SPI
                </div>
                <div class="col-4 ">
                    <?php $rows && $totalopenmsg ? $progresOpen = (count($totalopenmsg) / count($rows)) * 100 : $progresOpen = 0; ?>
                    <div class="progress" style="height:7px;">
                        <div class="progress-bar rounded" role="progressbar" style="width:<?= $progresOpen ?>%;" aria-valuenow="<?= $progresOpen ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="col-4 ">
                    <?php $rows && $totalclosemsg ? $progresClose = (count($totalclosemsg) / count($rows)) * 100 : $progresClose = 0 ?>
                    <div class="progress" style="height:7px;">
                        <div class="progress-bar rounded" role="progressbar" style="width:<?= $progresClose ?>%;" aria-valuenow="<?= $progresClose ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

            </div>
            <div class='col-12 rounded mt-3' style="height: 7px; background-color:#0d6efd;"></div>
        </div>



        <div class="col rounded p-4" style="background-color: #5c9bd1;">
            <div class="row text-white">
                <div class="col-3 h4">
                    TOTAL
                </div>
                <div class="col-3 h4">
                    <?= $arrMsgBelumBalas ? count($arrMsgBelumBalas) : 0 ?>
                </div>
                <div class="col-3 h4">
                    <?= $resultLanjutan ? count($resultLanjutan) : 0 ?>
                </div>
                <div class="col-3 h4">
                    <?= $totallebih24jam ? count($totallebih24jam) : 0 ?>
                    <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                </div>
            </div>
            <div class="row text-white">
                <div class="col-3 h4">
                    <?php
                    if ($arrMsgBelumBalas && $resultLanjutan) {
                        echo count($arrMsgBelumBalas) +  count($resultLanjutan);
                    } elseif ($arrMsgBelumBalas) {
                        echo count($arrMsgBelumBalas);
                    } elseif ($resultLanjutan) {
                        echo count($resultLanjutan);
                    } else {
                        echo 0;
                    }
                    ?>
                    <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                </div>
                <div class="col-3 " style="font-size: 14px; font-weight:bold;">
                    BELUM DIJAWAB
                </div>
                <div class="col-3 " style="font-size: 14px; font-weight:bold;">
                    LANJUTAN
                </div>
                <div class="col-3 " style="font-size: 14px; font-weight:bold;">
                    >24JAM
                </div>
            </div>
            <div class="row ">
                <div class="col-3 text-white" style="font-size: 14px; font-weight:bold;">
                    PERLU DIBALAS
                </div>
                <div class="col-3 ">
                    <div class="progress" style="height:7px;">
                        <!-- <?php $arrMsgBelumBalas || count($resultLanjutan)  ? $progresBelumBalas = (count($arrMsgBelumBalas) / (count($arrMsgBelumBalas) + count($resultLanjutan))) * 100 : 0;
                                ?> -->
                        <div class="progress-bar rounded" role="progressbar" style="width:<?= $progresBelumBalas ?>%;" aria-valuenow="<?= $progresBelumBalas ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="progress" style="height:7px;">
                        <!-- <?php $arrMsgBelumBalas || count($resultLanjutan)  ? $progresLanjutan = (count($resultLanjutan) / (count($arrMsgBelumBalas) + count($resultLanjutan))) * 100 : 0;
                                ?> -->
                        <div class="progress-bar rounded" role="progressbar" style="width:<?= $progresLanjutan ?>%;" aria-valuenow="<?= $progresLanjutan ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="progress" style="height:7px;">
                        <?php $totallebih24jam ? $progreslebih24jam =  count($totallebih24jam) / (count($totallebih24jam) / count($rows)) * 100 : 0 ?>
                        <div class="progress-bar rounded" role="progressbar" style="width:<?= $progreslebih24jam ?>%;" aria-valuenow="<?= $totallebih24jam ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

            </div>
            <div class='col-12 rounded mt-3' style="height: 7px; background-color:#0d6efd;"></div>
        </div>
    </div>

    <div class="col-12 d-flex justify-content-between">
        <?php echo $rows > $limit || true ? UI::showPaging('', $page, $limit_arr, $limit, $list) : null ?>
    </div>
    <div class="row mt-3">
        <?php foreach ($rows as $r) { ?>
            <div class="col-12 block-a mt-3 <?php if (!$r['is_read'] && $r['is_user']) {
                                                echo "block-new";
                                            } ?>">
                <div class="row">
                    <div class="col-8">
                        <table>
                            <tr>
                                <td>Tanggal</td>
                                <td class="px-2">:</td>
                                <td><?= StrDifTime(date('d-m-Y H:i:s'), $r['time']) ?> </td>
                            </tr>
                            <tr>
                                <td>Pengawal</td>
                                <td class="px-2">:</td>
                                <td><?= $r['nama'] ?></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td class="px-2">:</td>
                                <td class=''>
                                    <p class="py-1 box-status px-2 rounded text-dark <?= $r['status'] == 2 ? "bg-danger" : "bg-green" ?>"><?= $r['status'] == 2 ? "Close" : "Open" ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>Kepuasan</td>
                                <td class="px-2">:</td>
                                <td>
                                    <?= $ratingarr[$r['rating']] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Topik</td>
                                <td class="px-2">:</td>
                                <td><?= $r['topik'] ?></td>
                            </tr>
                            <tr>
                                <td>Pesan Teakhir Oleh</td>
                                <td class="px-2">:</td>
                                <td><?php
                                    if ($getMesaggeAkhir[$r['id_message']]['nama']) {
                                        echo $getMesaggeAkhir[$r['id_message']]['nama'];
                                    } else {
                                        echo $r['nama'];
                                    };
                                    if ($totallebih24jam[$r['id_message']]) {
                                        echo '<i class="bi bi-exclamation-triangle-fill text-danger"></i>';
                                    }
                                    if ($arrMsgBelumBalas[$r['id_message']]) {
                                        echo '<i class="bi bi-exclamation-triangle-fill text-warning"></i>';
                                    }
                                    // dpr($resultLanjutan, 1);
                                    if ($resultLanjutan[$r['id_message']]) {
                                        echo '<i class="bi bi-exclamation-triangle-fill text-warning"></i>';
                                    }
                                    ?></td>
                            </tr>
                            <tr>
                                <td>Pertanyaan</td>
                                <td class="px-2">:</td>
                            </tr>
                        </table>
                        <p>
                            <?php if ($getMesaggeAkhir[$r['id_message']]['msg']) {
                                echo $getMesaggeAkhir[$r['id_message']]['msg'];
                            } else {
                                echo $r['msg'];
                            } ?>
                        </p>
                    </div>
                    <div class="col-4 d-flex align-items-center justify-content-end"><a href="<?= site_url("panelbackend/consulting/detail/$r[id_message]") ?>" class="btn btn-primary">Lihat Detail</a></div>
                </div>
            </div>

        <?php } ?>
    </div>

    <style type="text/css">
        .block-a {
            display: block;
            padding: 10px 20px;
            border-bottom: 2px solid #dddddd;
            color: #333;
            background: #fff;
        }

        .block-new {
            background: #dce9ff;
        }

        .block-a:hover {
            background: #cee0ff;
            color: #333;
        }

        .box-status {
            width: fit-content;
            font-weight: bold;
            margin: 0px;
        }

        .bg-green {
            background-color: #36d7b6;

        }
    </style>
</div>