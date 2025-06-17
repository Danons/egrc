<div class="block-header home">
    <div class="float-left" style="width: auto;">
       <?= (count($scorecardarr) > 2 ? UI::createSelect('id_scorecard_filter', $scorecardarr, $id_scorecard_filter, true, 'form-control select2', "style='width:200px !important; display:inline' onchange='goSubmit(\"set_filter\")'") : null) ?>
        <?= UI::createSelect("id_taksonomi_objective_filter", array('' => '-Taksonomi-') + $taksonomiarr, $id_taksonomi_objective_filter, true, 'form-control', "style='width:auto !important; display:inline' onchange='goSubmit(\"set_filter\")'") ?>
        <?= (count($tingkataggregasiarr)>1 ? UI::createSelect("id_tingkat_agregasi_risiko_filter", array('' => '-Tingkat delegasi risiko-') + $tingkataggregasiarr, $id_tingkat_agregasi_risiko_filter, true, 'form-control', "style='width:auto !important; display:inline' onchange='goSubmit(\"set_filter\")'") : null) ?>

    </div>
    <div class="float-right">
        <?= UI::createTextNumber("tahun_filter", $tahun_filter, 4, 4, true, "form-control", "style='width:80px; display:inline' onchange='goSubmit(\"set_filter\")'") ?>
        <?= UI::createSelect("id_periode_tw_filter", $mtperiodetwarr, $id_periode_tw_filter, true, 'form-control', "style='width:120px !important; display:inline' onchange='goSubmit(\"set_filter\")'") ?>
    </div>
    <div style="clear: both;"></div>
</div>


<div class="row clearfix" style="position: relative;">
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <div class="card card-dashboard">
            <div class="header" style="text-align: center">
                <h2 class="dark-tooltip" data-bs-toggle="tooltip" title="<?= $this->config->item("keterangan_inheren_risk") ?>">INHEREN
                </h2>
            </div>
            <div class="body">
                <div id="donut_chart1" class="dashboard-donut-chart"></div>
            </div>
        </div>
    </div>
    <span class="bi bi-arrow-right panah panah1" style="background-image: url(<?= base_url('assets/images/panah.png') ?>);"></span>
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <div class="card card-dashboard">
            <div class="header" style="text-align: center">
                <h2 class="dark-tooltip" data-bs-toggle="tooltip" title="<?= $this->config->item("keterangan_current_risk") ?>">CONTROL
                </h2>
            </div>
            <div class="body">
                <div id="donut_chart2" class="dashboard-donut-chart"></div>
            </div>
        </div>
    </div>
    <span class="bi bi-arrow-right panah panah2" style="background-image: url(<?= base_url('assets/images/panah.png') ?>);"></span>
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <div class="card card-dashboard">
            <div class="header" style="text-align: center">
                <h2 class="dark-tooltip" data-bs-toggle="tooltip" title="<?= $this->config->item("keterangan_actual_risk") ?>">ACTUAL
                </h2>
            </div>
            <div class="body">
                <div id="donut_chart3" class="dashboard-donut-chart"></div>
            </div>
        </div>
    </div>
    <span class="bi bi-arrow-right panah panah3" style="background-image: url(<?= base_url('assets/images/panah.png') ?>);"></span>
    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <div class="card card-dashboard">
            <div class="header" style="text-align: center">
                <h2 class="dark-tooltip" data-bs-toggle="tooltip" title="<?= $this->config->item("keterangan_residual_risk") ?>">TARGETED
                </h2>
            </div>
            <div class="body">
                <div id="donut_chart4" class="dashboard-donut-chart"></div>
            </div>
        </div>
    </div>
</div>


<div class="row clearfix">
    <div class="col-xs-12 col-sm-12">
        <div class="card card-dashboard">
            <div class="header">
                <!-- <a class="button6" data-bs-toggle="collapse" data-bs-target="#filter">FILTER</a>
            <h1 style="margin: 0px; text-align: center; margin-top: -40px;">TOP RISIKO</h1> -->
                <center>
                    <H1 style="margin: 0px">TOP
                        <?= UI::createSelect(
                            "top_filter",
                            array('' => '-top-', '10' => '10', '20' => '20', '30' => '30', '50' => '50', '100' => '100'),
                            $top_filter,
                            true,
                            'form-control',
                            "style='width:auto !important; display:inline' onchange='goSubmit(\"set_filter\")'"
                        ) ?>
                        RISIKO</H1>
                </center>
            </div>
            <div class="body" style="padding: 15px 8px;">
                <?php
                $is_css = false;
                include "laporanriskprofileprint1.php";
                ?>
            </div>
        </div>
    </div>
</div>

<?php
if (($pengumumanarr)) { ?>
    <div class="modal fade" id="pengumuman" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="pengumumanLabel">Pengumuman</h4>
                </div>
                <div class="modal-body" style="color:#333">
                    <?php foreach ($pengumumanarr as $rmsg) {
                        echo nl2br($rmsg['msg']); ?>
                        <br />
                        <a style="font-size: 11px" href="<?= site_url('panelbackend/home/msg/' . $rmsg['id_msg']) ?>">Jangan Tampilkan Lagi</a>
                        <br />
                        <br />
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary  btn-sm" data-bs-dismiss="modal">CLOSE</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(function() {
            $('#pengumuman').modal('show');
        })
    </script>
<?php } ?>
<!-- <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script> -->
<script src="<?= base_url('assets/js/core.js') ?>"></script>
<script src="<?= base_url('assets/js/charts.js') ?>"></script>
<script src="<?= base_url('assets/js/animated.js') ?>"></script>
<?php
$temp_warna = array();
foreach ($rs_matrix as $r) {
    $temp_warna[$r['nama']] = $r['warna'];
}

$inheren = $total['inheren'];
$control = $total['control'];
$actual = $total['actual'];
$residual = $total['residual'];
?>
<script type="text/javascript">
    am4core.ready(function() {

        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end

        // Create chart instance
        var chart = am4core.create("donut_chart1", am4charts.PieChart3D);

        // Add and configure Series
        var pieSeries = chart.series.push(new am4charts.PieSeries3D());
        pieSeries.dataFields.value = "jumlah";
        pieSeries.dataFields.category = "tingkat";

        // Let's cut a hole in our Pie chart the size of 30% the radius
        // chart.innerRadius = am4core.percent(30);

        // Put a thick white border around each Slice
        // pieSeries.slices.template.stroke = am4core.color("#fff");
        // pieSeries.slices.template.strokeWidth = 2;
        // pieSeries.slices.template.strokeOpacity = 1;
        pieSeries.slices.template
            // change the cursor on hover to make it apparent the object can be interacted with
            .cursorOverStyle = [{
                "property": "cursor",
                "value": "pointer"
            }];

        pieSeries.alignLabels = false;
        // pieSeries.labels.template.bent = true;
        // pieSeries.labels.template.radius = 3;
        pieSeries.labels.template.padding(0, 0, 0, 0);
        pieSeries.labels.template.fill = am4core.color("#000000");

        pieSeries.ticks.template.disabled = true;

        //color
        pieSeries.slices.template.propertyFields.fill = "color";

        // Create a base filter effect (as if it's not there) for the hover to return to
        var shadow = pieSeries.slices.template.filters.push(new am4core.DropShadowFilter);
        shadow.opacity = 0;

        // Create hover state
        var hoverState = pieSeries.slices.template.states.getkey("hover"); // normally we have to create the hover state, in this case it already exists

        // Slightly shift the shadow and make it more prominent on hover
        var hoverShadow = hoverState.filters.push(new am4core.DropShadowFilter);
        hoverShadow.opacity = 0.7;
        hoverShadow.blur = 5;

        // Add a legend
        // chart.legend = new am4charts.Legend();

        chart.data = [
            <?php foreach ($inheren as $label => $count) {
                if (!$count) continue; ?> {
                    tingkat: "<?php echo $label; ?>",
                    jumlah: <?= $count ?>,
                    color: am4core.color("<?= $temp_warna[$label] ?>")
                },
            <?php } ?>
        ];



        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end

        // Create chart instance
        var chart = am4core.create("donut_chart2", am4charts.PieChart3D);

        // Add and configure Series
        var pieSeries = chart.series.push(new am4charts.PieSeries3D());
        pieSeries.dataFields.value = "jumlah";
        pieSeries.dataFields.category = "tingkat";

        // Let's cut a hole in our Pie chart the size of 30% the radius
        // chart.innerRadius = am4core.percent(30);

        // Put a thick white border around each Slice
        // pieSeries.slices.template.stroke = am4core.color("#fff");
        // pieSeries.slices.template.strokeWidth = 2;
        // pieSeries.slices.template.strokeOpacity = 1;
        pieSeries.slices.template
            // change the cursor on hover to make it apparent the object can be interacted with
            .cursorOverStyle = [{
                "property": "cursor",
                "value": "pointer"
            }];

        pieSeries.alignLabels = false;
        // pieSeries.labels.template.bent = true;
        // pieSeries.labels.template.radius = 3;
        pieSeries.labels.template.padding(0, 0, 0, 0);
        pieSeries.labels.template.fill = am4core.color("#000000");

        pieSeries.ticks.template.disabled = true;

        //color
        pieSeries.slices.template.propertyFields.fill = "color";

        // Create a base filter effect (as if it's not there) for the hover to return to
        var shadow = pieSeries.slices.template.filters.push(new am4core.DropShadowFilter);
        shadow.opacity = 0;

        // Create hover state
        var hoverState = pieSeries.slices.template.states.getkey("hover"); // normally we have to create the hover state, in this case it already exists

        // Slightly shift the shadow and make it more prominent on hover
        var hoverShadow = hoverState.filters.push(new am4core.DropShadowFilter);
        hoverShadow.opacity = 0.7;
        hoverShadow.blur = 5;

        // Add a legend
        // chart.legend = new am4charts.Legend();

        chart.data = [
            <?php foreach ($control as $label => $count) {
                if (!$count) continue; ?> {
                    tingkat: "<?php echo $label; ?>",
                    jumlah: <?= $count ?>,
                    color: am4core.color("<?= $temp_warna[$label] ?>")
                },
            <?php } ?>
        ];


        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end

        // Create chart instance
        var chart = am4core.create("donut_chart3", am4charts.PieChart3D);

        // Add and configure Series
        var pieSeries = chart.series.push(new am4charts.PieSeries3D());
        pieSeries.dataFields.value = "jumlah";
        pieSeries.dataFields.category = "tingkat";

        // Let's cut a hole in our Pie chart the size of 30% the radius
        // chart.innerRadius = am4core.percent(30);

        // Put a thick white border around each Slice
        // pieSeries.slices.template.stroke = am4core.color("#fff");
        // pieSeries.slices.template.strokeWidth = 2;
        // pieSeries.slices.template.strokeOpacity = 1;
        pieSeries.slices.template
            // change the cursor on hover to make it apparent the object can be interacted with
            .cursorOverStyle = [{
                "property": "cursor",
                "value": "pointer"
            }];

        pieSeries.alignLabels = false;
        // pieSeries.labels.template.bent = true;
        // pieSeries.labels.template.radius = 3;
        pieSeries.labels.template.padding(0, 0, 0, 0);
        pieSeries.labels.template.fill = am4core.color("#000000");

        pieSeries.ticks.template.disabled = true;

        //color
        pieSeries.slices.template.propertyFields.fill = "color";

        // Create a base filter effect (as if it's not there) for the hover to return to
        var shadow = pieSeries.slices.template.filters.push(new am4core.DropShadowFilter);
        shadow.opacity = 0;

        // Create hover state
        var hoverState = pieSeries.slices.template.states.getkey("hover"); // normally we have to create the hover state, in this case it already exists

        // Slightly shift the shadow and make it more prominent on hover
        var hoverShadow = hoverState.filters.push(new am4core.DropShadowFilter);
        hoverShadow.opacity = 0.7;
        hoverShadow.blur = 5;

        // Add a legend
        // chart.legend = new am4charts.Legend();

        chart.data = [
            <?php foreach ($actual as $label => $count) {
                if (!$count) continue; ?> {
                    tingkat: "<?php echo $label; ?>",
                    jumlah: <?= $count ?>,
                    color: am4core.color("<?= $temp_warna[$label] ?>")
                },
            <?php } ?>
        ];


        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end

        // Create chart instance
        var chart = am4core.create("donut_chart4", am4charts.PieChart3D);

        // Add and configure Series
        var pieSeries = chart.series.push(new am4charts.PieSeries3D());
        pieSeries.dataFields.value = "jumlah";
        pieSeries.dataFields.category = "tingkat";

        // Let's cut a hole in our Pie chart the size of 30% the radius
        // chart.innerRadius = am4core.percent(30);

        // Put a thick white border around each Slice
        // pieSeries.slices.template.stroke = am4core.color("#fff");
        // pieSeries.slices.template.strokeWidth = 2;
        // pieSeries.slices.template.strokeOpacity = 1;
        pieSeries.slices.template
            // change the cursor on hover to make it apparent the object can be interacted with
            .cursorOverStyle = [{
                "property": "cursor",
                "value": "pointer"
            }];

        pieSeries.alignLabels = false;
        // pieSeries.labels.template.bent = true;
        // pieSeries.labels.template.radius = 3;
        pieSeries.labels.template.padding(0, 0, 0, 0);
        pieSeries.labels.template.fill = am4core.color("#000000");

        pieSeries.ticks.template.disabled = true;

        //color
        pieSeries.slices.template.propertyFields.fill = "color";

        // Create a base filter effect (as if it's not there) for the hover to return to
        var shadow = pieSeries.slices.template.filters.push(new am4core.DropShadowFilter);
        shadow.opacity = 0;

        // Create hover state
        var hoverState = pieSeries.slices.template.states.getkey("hover"); // normally we have to create the hover state, in this case it already exists

        // Slightly shift the shadow and make it more prominent on hover
        var hoverShadow = hoverState.filters.push(new am4core.DropShadowFilter);
        hoverShadow.opacity = 0.7;
        hoverShadow.blur = 5;

        // Add a legend
        // chart.legend = new am4charts.Legend();

        chart.data = [
            <?php foreach ($residual as $label => $count) {
                if (!$count) continue;
            ?> {
                    tingkat: "<?php echo $label; ?>",
                    jumlah: <?= $count ?>,
                    color: am4core.color("<?= $temp_warna[$label] ?>")
                },
            <?php } ?>
        ];

    }); // end am4core.ready()
    $(document).ready(function() {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
<style>
    g[aria-labelledby="id-66-title"],
    g[aria-labelledby="id-142-title"],
    g[aria-labelledby="id-218-title"],
    g[aria-labelledby="id-294-title"],
    g[aria-labelledby="id-145-title"],
    g[aria-labelledby="id-224-title"],
    g[aria-labelledby="id-303-title"] {
        display: none;
    }

    text>tspan {
        font-family: "Lato", sans-serif;
        font-size: 11px !important;
    }

    .dark-tooltip+.tooltip>.tooltip-inner {
        background-color: #000c !important;
        border: 1px solid #000c;
    }

    .header .form-line {
        display: inline;
    }

    .panah {
        /* background-image: -webkit-gradient(linear, 0% 50%, 100% 100%, from(#ffffff), to(#e6e6e6)) !important;
        font-size: 100px; */
        /*   margin-left: -50px;
    margin-top: 320px;*/
        position: absolute;
        z-index: 10;
        /* text-shadow: 0px 5px 5px rgba(0, 0, 0, .2);
        color: transparent;
        -webkit-background-clip: text;
        background-clip: text; */
    }

    .panah1 {
        -webkit-animation: myrighta 4s;
        /* Safari 4.0 - 8.0 */
        animation: myrighta 4s;
        top: 130px;
        right: 71%;
    }

    .panah2 {
        -webkit-animation: myrightb 4s;
        /* Safari 4.0 - 8.0 */
        animation: myrightb 4s;
        top: 130px;
        right: 46%;
    }

    .panah3 {
        -webkit-animation: myrightc 4s;
        /* Safari 4.0 - 8.0 */
        animation: myrightc 4s;
        top: 130px;
        right: 21%;
    }


    /* ! my new style */
    .panah {
        /* background-image: unset !important; */
        font-size: unset !important;
        position: absolute;
        z-index: 10;
        text-shadow: unset !important;
        color: unset !important;
        -webkit-background-clip: unset !important;
        background-clip: unset !important;
        width: 100px;
        height: 100px;
        background-position: center;
        background-size: 70%;
        background-repeat: no-repeat;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #2a7489 !important;
        filter: drop-shadow(0px 3px 5px rgba(0, 0, 0, 0.2));
    }


    @media (max-width: 991px) {
        .panah {
            display: none;
        }
    }



    @-webkit-idkeyframes myrighta {
        0% {
            opacity: 0;
            right: 77%;
        }

        60% {
            opacity: 0;
            right: 77%;
        }

        70% {
            right: 67%;
            opacity: 1;
        }
    }

    @idkeyframes myrighta {
        0% {
            opacity: 0;
            right: 77%;
        }

        60% {
            opacity: 0;
            right: 77%;
        }

        70% {
            right: 67%;
            opacity: 1;
        }
    }

    @-webkit-idkeyframes myrightb {
        0% {
            right: 52%;
            opacity: 0;
        }

        70% {
            right: 52%;
            opacity: 0;
        }

        80% {
            right: 42%;
            opacity: 1;
        }
    }

    @idkeyframes myrightb {
        0% {
            right: 52%;
            opacity: 0;
        }

        70% {
            right: 52%;
            opacity: 0;
        }

        80% {
            right: 42%;
            opacity: 1;
        }
    }

    @-webkit-idkeyframes myrightc {
        0% {
            right: 27%;
            opacity: 0;
        }

        80% {
            right: 27%;
            opacity: 0;
        }

        90% {
            right: 17%;
            opacity: 1;
        }
    }

    @idkeyframes myrightc {
        0% {
            right: 27%;
            opacity: 0;
        }

        80% {
            right: 27%;
            opacity: 0;
        }

        90% {
            right: 17%;
            opacity: 1;
        }
    }
</style>