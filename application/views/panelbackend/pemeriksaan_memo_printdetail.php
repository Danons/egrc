<div style="margin: 0px 20px; width: 100%;">
    <div style="width: 100%; display: flex; justify-content: center;">
        <img src="../../../assets/images/logo.png" alt="" height="100px">
    </div>
    <div style="width: 100%; ">
        <h1 class="text-center" style="margin: 30px 0px; font-size: 15px;">MEMO</h1>
        <div>
            <table>
                <tr>
                    <td>Dari</td>
                    <td>:</td>
                    <td><?= $row['dari'] ?></td>
                </tr>
                <tr>
                    <td>Kepada</td>
                    <td>:</td>
                    <td><?= $row['ke'] ?></td>
                </tr>
                <tr>
                    <td>Isi</td>
                    <td>:</td>
                </tr>
            </table>
            <p style="padding: 3px;"><?= $row['isi'] ?></p>
            <div style=" width: 100%; padding: 10px 40px; justify-content: flex-end; display: flex;">
                <div style="display: flex; justify-content: center; flex-direction: column; text-align: center;">
                    <p><?= $row['tempat'] . ',&nbsp;' . Eng2Ind($row['tanggal_surat'], false) ?></p>
                    <p>Direksi,</p>
                    <p style="margin-top: 30px;"><?= $userarr[$row['direksi']] ?></p>
                </div>
            </div>
        </div>

    </div>

</div>