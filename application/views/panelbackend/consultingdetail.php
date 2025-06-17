<div class="modal fade " id="ratingModel" tabindex="-1" role="dialog" aria-labelledby="ratingModelLabel" aria-modal="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="ratingModelLabel">Pesan Telah Selesai <br />Beri Rating Pada SPI</h6>
            </div>
            <div class="modal-body">
                <?php
                echo UI::createSelect('rating', $ratingarr, $this->post['rating'], $edited, $class = 'form-control', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
            </div>
        </div>
    </div>
</div>


<script src="<?php echo base_url() ?>assets/plugins/select2/select2.min.js"></script>
<script src="<?php echo base_url() ?>assets/plugins/jquery/jquery.min.js"></script>
<?php
if ($test) { ?>
    <script>
        jQuery(document).ready(function($) {
            $('#ratingModel').modal('show')
            $('.js-example-basic-single').select2({
                placeholder: 'select an option',
                dropdownParent: 'ratingModel',
            })

        });
    </script>
<?php } ?>




<div class="row rowmsg" style="margin-top: -5px;">
    <div class="col-sm-12">
        <?php if ($rows) { ?>
            <table>
                <tr>
                    <td>Pengawal</td>
                    <td>:</td>
                    <td><?= $rows[0]["nama"] ?></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td class=''>
                        <p class="py-1  px-2 rounded text-dark <?= $rows[0]['status'] == 1 ? "bg-green" : "bg-danger" ?>"><?= $rows[0]['status'] == 1 ? "Open" : "Close" ?></p>
                    </td>
                </tr>
                <tr>
                    <td>Topik</td>
                    <td>:</td>
                    <td><?= $rows[0]["topik"] ?></td>
                </tr>
            </table>
            <?php
            foreach ($rows as $r) {
                // dpr($r);

                if (!$this->data['view_all']) {
                    $isuserparams = 1;
                } else {
                    $isuserparams = 0;
                    if ($r['is_user'] == 2) {
                        $bot_params = 2;
                    }
                }
                if ($r['is_user'] == $isuserparams || $r['is_user'] == $bot_params) {

                    if (!$r['nama'])
                        $r['nama'] = "Bot"
            ?>
                    <div class="col-9 ms-auto chat chat-right">
                        <!-- <img src="<?= base_url() . "assets/img/akuarip.jpg" ?>" alt="Avatar" <?php if (!$_SESSION[SESSION_APP]['login']) { ?>class="right" <?php } ?>> -->
                        <div style="color:#423a3b;" class='right-text'>
                            <span style="font-weight: bold;"><?= $r['nama'] ?></span>
                            <span style="font-size: 75%;"><?= StrDifTime(date('d-m-Y H:i:s'), $r['time']) ?></span>
                        </div>
                        <div class="containerp mt-1">
                            <?= $r['msg'] ?>
                        </div>
                        <?php foreach ($dataMsgFile[$r['id_message']] as $dmf) { ?>
                            <a target='_BLANK' href="<?= site_url($page_ctrl . "/open_filem/" . $dmf['id_message_files']) ?>" class="btn text-white m-0" style='background-color: #35c5d4;'><?= $dmf["client_name"] ?></a>
                        <?php } ?>

                    </div>
                <?php
                } else {
                ?>

                    <div class="col-9 chat">
                        <!-- <img src="<?= base_url() . "assets/img/akuarip.jpg" ?>" alt="Avatar" <?php if (!$_SESSION[SESSION_APP]['login']) { ?>class="right" <?php } ?>> -->
                        <div style="color:#423a3b;" class='right-text'>
                            <span style="font-weight: bold;"><?= $r['nama'] ?></span>
                            <span style="font-size: 75%;"><?= StrDifTime(date('d-m-Y H:i:s'), $r['time']) ?></span>
                        </div>
                        <div class="containerp mt-1">
                            <?= $r['msg'] ?>
                        </div>
                        <?php foreach ($dataMsgFile[$r['id_message']] as $dmf) { ?>
                            <a target='_BLANK' href="<?= site_url($page_ctrl . "/open_filem/" . $dmf['id_message_files']) ?>" class="btn text-white m-0" style='background-color: #5c9bd1;'><?= $dmf["client_name"] ?></a>
                        <?php } ?>

                    </div>

            <?php }
            } ?>
        <?php } else { ?>
            <?php if ($mode != 'add') {  ?>
                <div class="alert alert-warning">Pesan kosong</div>
            <?php } else {
                $from = UI::createTextBox('topik', $this->post['topik'], null, null, true, 'form-control');
                echo UI::createFormGroup($from, null, null, 'Topik', true);
            } ?>
        <?php } ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <?php
        // dpr($arrtemplate, 1);
        if ($mode != 'add') {
            $this->post['topik'] = $rows[0]['topik'];
            echo UI::createSelect('templatemsg', $arrTemplate, $this->post['templatemsg'], $edited, 'form-control', 'style="width:100%;" onchange=\'goSubmit("set_value")\'');
        }

        if ($this->post['templatemsg']) {
            $this->post['msg'] .= $getTemplateMsg['msg'];
        }

        $from = UI::createTextArea("msg", $this->post['msg'], null, null, true, 'form-control mt-2 contents-mini', 'placeholder="Pesan..."');
        $from .= "<br/>" . UI::createUploadMultipleMessages("file_messages", $this->post['file_messages'], $page_ctrl, true, "Select files...");
        echo UI::createFormGroup($from, null, null, null, true);
        ?>
        <div class="d-flex justify-content-between" style="">
            <?php
            if ($rows[0]["status"] == 0) {
                $status = 'open';
            } else {
                $status = 'close';
            }
            if ($this->data['view_all']) {
                $arrStatus = array(
                    1 => 'Open',
                    2 => "Close"
                );
                $from = UI::createSelect('status', $arrStatus, $this->post['status'], $edited, $class = 'form-control', "style='width:100%;' onchange='goSubmit(\"set_value\")'");
                echo UI::createFormGroup($from, null, "status", "Status", false);
            }
            // dpr($this->post['status']);
            ?>
            <div class="ms-auto">
                <button type="submit" class="btn waves-effect btn-default"><span class="glyphicon glyphicon-repeat"></span> Reload</button>
                <button type="submit" class="btn-save btn btn-success" onclick="goSave()"><span class="glyphicon glyphicon-floppy-save"></span> Send</button>
            </div>



        </div>
        <script>
            function goSave() {
                $("#main_form").submit(function(e) {
                    if (e) {
                        $(".btn-save").attr("disabled", "disabled");
                        $("#act").val("save");
                    } else {
                        return false;
                    }
                });

            }

            function goBatal() {
                $("#act").val('reset');
                $("#main_form").submit();
            }
            $(function() {
                $(".rowmsg").scrollTop($(document).height());
            })
        </script>
    </div>

    <style type="text/css">
        .rowmsg {
            <?php if ($mode != 'add') { ?>overflow-y: auto;
            height: 400px;
            margin-bottom: 10px;
            <?php } ?>
        }

        .containerp p {
            margin: 0px;
        }

        .bg-green {
            width: fit-content;
            background-color: #36d7b6;
            font-weight: bold;
            margin: 0px;
        }

        .chat {
            background-color: #36d7b6;
            border-radius: 5px;
            padding: 5px;
            margin: 10px 0;
            box-shadow: 0 0 2px 0px #88888833;
        }

        /* Darker chat chat */
        .darker {
            border-color: #ccc;
            background-color: #ddd;
        }

        /* Clear floats */
        .chat::after {
            content: "";
            clear: both;
            display: table;
        }

        /* Style images */
        .chat img {
            float: left;
            max-width: 60px;
            width: 100%;
            margin-right: 20px;
            border-radius: 50%;
        }

        /* Style the right image */
        .chat img.right {
            float: right;
            margin-left: 20px;
            margin-right: 0;
        }

        /* Style time text */
        .time-right {
            float: right;
            color: #aaa;
            zoom: 0.7;
        }

        .right-text {
            border-bottom: 1px solid black;
        }

        /* Style time text */
        .time-left {
            float: left;
            color: #aaa;
            zoom: 0.7;
        }

        .chat-right {

            background-color: #5c9bd1;
            border-color: #423a3b;
        }

        .select2-container--open {
            z-index: 9999999999999999999;
        }
    </style>
</div>