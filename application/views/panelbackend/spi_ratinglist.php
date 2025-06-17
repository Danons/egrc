<div class="" style="width: 100%;">
    <h3>Rating Consulting</h3>
    <div class="col-3 rounded me-2 p-3 text-white" style="background-color: #5c9bd1;">
        <p style="font-size: 22px; margin: 0px; display: flex; align-items: center;"><i style="font-size: 10px;" class="me-1 bi bi-star-fill text-white"></i><?= $rating ?></p>
        <p style="font-size: 17px; margin: 0px; display: flex; align-items: center;"><?= $teksRating ?> | <?= $jumlah_data['jumlah_data'] ?> reviews </p>
    </div>
    <h3 class="mt-4">Rating Kegiatan</h3>
    <div style="display: flex;   overflow-x: scroll; width: 100%;" class=" scrollRating">
        <?php
        $no = 1;
        foreach ($rating_kegiatan as $val) {
            $no++; ?>
            <div class="col-3 rounded me-2 p-3 text-white" style="background-color:  <?= $no % 2 == 0 ? '#5c9bd1' : '#36d7b6' ?>;">
                <p style="font-size: 22px; margin: 0px;"><?= $val['nama'] ?></p>
                <p style="font-size: 17px; margin: 0px; display: flex; align-items: center;"><i style="font-size: 10px;" class="me-1 bi bi-star-fill text-white"></i><?= round($val['nilai'] / $val['jumlah_data'],2) ?> | <?= $val['jumlah_data'] ?> reviews </p>
            </div>
        <?php } ?>
    </div>
    <h3 class="mt-4">Rating Tahunan</h3>
    <div style="display: flex;   overflow-x: scroll; width: 100%;" class=" scrollRating">
        <?php
        $no = 1;
        foreach ($rating_tahun as $val) {
            $no++; ?>
            <div class="col-3 rounded me-2 p-3 text-white" style="background-color:  <?= $no % 2 == 0 ? '#5c9bd1' : '#36d7b6' ?>;">
                <p style="font-size: 22px; margin: 0px;"><?= $val['tahun'] ?></p>
                <p style="font-size: 17px; margin: 0px; display: flex; align-items: center;"><i style="font-size: 10px;" class="me-1 bi bi-star-fill text-white"></i><?= round($val['nilai'] / $val['jumlah_data'],2) ?> | <?= $val['jumlah_data'] ?> reviews </p>
            </div>
        <?php } ?>
    </div>

</div>

<style>
    .scrollRating::-webkit-scrollbar {
        display: none;
    }

    .scrollRating {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>