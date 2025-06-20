<?php
// dpr($nid);
// dpr($pengawasarr);
?>
<ul class="nav nav-tabs d-flex flex-nowrap" style="overflow-x:scroll; overflow-y:hidden;">
    <?php $no = 1;
    foreach ($pengawasarr as $k => $v) { ?>
        <li class="nav-item" style="display: flex; text-wrap:nowrap;">
            <?php if ($nid == $k) { ?>
                <a class="nav-link <?= $nid == $k ? 'active' : '' ?>" aria-current="page" href="#" onclick="goSubmitValue('set_nid','<?= $k ?>')"><?= $v ?></a>
            <?php   } else { ?>
                <a class="nav-link <?= $nid == $k ? 'active' : '' ?>" aria-current="page" href="#" onclick="goSubmitValue('set_nid','<?= $k ?>')"><?= $v ?></a>
            <?php } ?>
        </li>
    <?php $no++;
    } ?>
</ul>
<div class="portlet light bordered">
    <?= $contentprofil ?>
</div>

<style>
    .bg-default {
        background: #e1e5ec !important;
    }

    .table-condensed>tbody>tr>td,
    .table-condensed>tbody>tr>th,
    .table-condensed>tfoot>tr>td,
    .table-condensed>tfoot>tr>th,
    .table-condensed>thead>tr>td,
    .table-condensed>thead>tr>th {
        padding: 5px;
    }

    .portlet {
        margin-top: 0;
        margin-bottom: 25px;
        padding: 0;
        border-radius: 4px
    }

    .portlet.portlet-fullscreen {
        z-index: 10060;
        margin: 0;
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        width: 100%;
        height: 100%;
        background: #fff
    }

    .portlet.portlet-fullscreen>.portlet-body {
        overflow-y: auto;
        overflow-x: hidden;
        padding: 0 10px
    }

    .portlet.portlet-fullscreen>.portlet-title {
        padding: 0 10px
    }

    .portlet>.portlet-title {
        border-bottom: 1px solid #eee;
        padding: 0;
        margin-bottom: 10px;
        min-height: 41px;
        -webkit-border-radius: 4px 4px 0 0;
        -moz-border-radius: 4px 4px 0 0;
        -ms-border-radius: 4px 4px 0 0;
        -o-border-radius: 4px 4px 0 0;
        border-radius: 4px 4px 0 0
    }

    .portlet>.portlet-title:after,
    .portlet>.portlet-title:before {
        content: " ";
        display: table
    }

    .portlet>.portlet-title:after {
        clear: both
    }

    .portlet>.portlet-title>.caption {
        float: left;
        display: inline-block;
        font-size: 18px;
        line-height: 18px;
        padding: 10px 0
    }

    .portlet>.portlet-title>.caption.bold {
        font-weight: 400
    }

    .portlet>.portlet-title>.caption>i {
        float: left;
        margin-top: 4px;
        display: inline-block;
        font-size: 13px;
        margin-right: 5px;
        color: #666
    }

    .portlet>.portlet-title>.caption>i.glyphicon {
        margin-top: 2px
    }

    .portlet>.portlet-title>.caption>.caption-helper {
        padding: 0;
        margin: 0;
        line-height: 13px;
        color: #9eacb4;
        font-size: 13px;
        font-weight: 400
    }

    .portlet>.portlet-title>.actions {
        float: right;
        display: inline-block;
        padding: 6px 0
    }

    .portlet>.portlet-title>.actions>.dropdown-menu i {
        color: #555
    }

    .portlet>.portlet-title>.actions>.btn,
    .portlet>.portlet-title>.actions>.btn-group>.btn,
    .portlet>.portlet-title>.actions>.btn-group>.btn.btn-sm,
    .portlet>.portlet-title>.actions>.btn.btn-sm {
        padding: 4px 10px;
        font-size: 13px;
        line-height: 1.5
    }

    .portlet>.portlet-title>.actions>.btn-group>.btn.btn-default,
    .portlet>.portlet-title>.actions>.btn-group>.btn.btn-sm.btn-default,
    .portlet>.portlet-title>.actions>.btn.btn-default,
    .portlet>.portlet-title>.actions>.btn.btn-sm.btn-default {
        padding: 3px 9px
    }

    .portlet>.portlet-title>.actions>.btn-group>.btn.btn-sm>i,
    .portlet>.portlet-title>.actions>.btn-group>.btn>i,
    .portlet>.portlet-title>.actions>.btn.btn-sm>i,
    .portlet>.portlet-title>.actions>.btn>i {
        font-size: 13px
    }

    .portlet>.portlet-title>.actions .btn-icon-only {
        padding: 5px 7px 3px
    }

    .portlet>.portlet-title>.actions .btn-icon-only.btn-default {
        padding: 4px 6px 2px
    }

    .portlet>.portlet-title>.actions .btn-icon-only.btn-default>i {
        font-size: 14px
    }

    .portlet>.portlet-title>.actions .btn-icon-only.btn-default.fullscreen {
        font-family: FontAwesome;
        color: #a0a0a0;
        padding-top: 3px
    }

    .portlet>.portlet-title>.actions .btn-icon-only.btn-default.fullscreen.btn-sm {
        padding: 3px !important;
        height: 27px;
        width: 27px
    }

    .portlet>.portlet-title>.actions .btn-icon-only.btn-default.fullscreen:before {
        content: "\f065"
    }

    .portlet>.portlet-title>.actions .btn-icon-only.btn-default.fullscreen.on:before {
        content: "\f066"
    }

    .portlet>.portlet-title>.tools {
        float: right;
        display: inline-block;
        padding: 12px 0 8px
    }

    .portlet>.portlet-title>.tools>a {
        display: inline-block;
        height: 16px;
        margin-left: 5px;
        opacity: 1;
        filter: alpha(opacity=100)
    }

    .portlet>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon.png);
        background-repeat: no-repeat;
        width: 11px
    }

    .portlet>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon.png);
        background-repeat: no-repeat;
        width: 12px
    }

    .portlet>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon.png);
        width: 13px
    }

    .portlet>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon.png);
        width: 14px;
        visibility: visible
    }

    .portlet>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon.png);
        width: 14px;
        visibility: visible
    }

    .portlet>.portlet-title>.tools>a.fullscreen {
        display: inline-block;
        top: -3px;
        position: relative;
        font-size: 13px;
        font-family: FontAwesome;
        color: #ACACAC
    }

    .portlet>.portlet-title>.tools>a.fullscreen:before {
        content: "\f065"
    }

    .portlet>.portlet-title>.tools>a.fullscreen.on:before {
        content: "\f066"
    }

    .portlet>.portlet-title>.tools>a:hover {
        text-decoration: none;
        -webkit-transition: all .1s ease-in-out;
        -moz-transition: all .1s ease-in-out;
        -o-transition: all .1s ease-in-out;
        -ms-transition: all .1s ease-in-out;
        transition: all .1s ease-in-out;
        opacity: .8;
        filter: alpha(opacity=80)
    }

    .portlet>.portlet-title>.pagination {
        float: right;
        display: inline-block;
        margin: 2px 0 0;
        border: 0;
        padding: 4px 0
    }

    .portlet>.portlet-title>.nav-tabs {
        background: 0 0;
        margin: 1px 0 0;
        float: right;
        display: inline-block;
        border: 0
    }

    .portlet>.portlet-title>.nav-tabs>li {
        background: 0 0;
        margin: 0;
        border: 0
    }

    .portlet>.portlet-title>.nav-tabs>li>a {
        background: 0 0;
        margin: 5px 0 0 1px;
        border: 0;
        padding: 8px 10px;
        color: #fff
    }

    .portlet>.portlet-body p,
    .table .btn {
        margin-top: 0
    }

    .portlet>.portlet-title>.nav-tabs>li.active>a,
    .portlet>.portlet-title>.nav-tabs>li:hover>a {
        color: #333;
        background: #fff;
        border: 0
    }

    .portlet>.portlet-body {
        clear: both;
        -webkit-border-radius: 0 0 4px 4px;
        -moz-border-radius: 0 0 4px 4px;
        -ms-border-radius: 0 0 4px 4px;
        -o-border-radius: 0 0 4px 4px;
        border-radius: 0 0 4px 4px
    }

    .portlet>.portlet-empty {
        min-height: 125px
    }

    .portlet.full-height-content {
        margin-bottom: 0
    }

    .portlet.bordered {
        border-left: 2px solid #e6e9ec !important
    }

    .portlet.bordered>.portlet-title {
        border-bottom: 0
    }

    .portlet.solid {
        padding: 0 10px 10px;
        border: 0
    }

    .portlet.solid>.portlet-title {
        border-bottom: 0;
        margin-bottom: 10px
    }

    .portlet.solid>.portlet-title>.caption {
        padding: 16px 0 2px
    }

    .portlet.solid>.portlet-title>.actions {
        padding: 12px 0 6px
    }

    .portlet.solid>.portlet-title>.tools {
        padding: 14px 0 6px
    }

    .portlet.solid.bordered>.portlet-title {
        margin-bottom: 10px
    }

    .portlet.box {
        padding: 0 !important
    }

    .portlet.box>.portlet-title {
        border-bottom: 0;
        padding: 0 10px;
        margin-bottom: 0;
        color: #fff
    }

    .portlet.box>.portlet-title>.caption {
        padding: 11px 0 9px
    }

    .portlet.box>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.box>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.box>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.box>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.box>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.box>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box>.portlet-title>.actions {
        padding: 7px 0 5px
    }

    .portlet.box>.portlet-body {
        background-color: #fff;
        padding: 15px
    }

    .portlet.light {
        padding: 12px 20px 15px;
        background-color: #fff
    }

    .portlet.light.bordered {
        border: 1px solid #e7ecf1 !important
    }

    .portlet.light.bordered>.portlet-title {
        border-bottom: 1px solid #eef1f5
    }

    .portlet.light.bg-inverse {
        background: #f1f4f7
    }

    .portlet.light>.portlet-title {
        padding: 0;
        min-height: 48px
    }

    .portlet.light>.portlet-title>.caption {
        color: #666;
        padding: 10px 0
    }

    .portlet.light>.portlet-title>.caption>.caption-subject {
        font-size: 16px
    }

    .portlet.light>.portlet-title>.caption>i {
        color: #777;
        font-size: 15px;
        font-weight: 300;
        margin-top: 3px
    }

    .portlet.solid.blue-chambray>.portlet-title>.caption,
    .portlet.solid.blue-dark>.portlet-title>.caption,
    .portlet.solid.blue-ebonyclay>.portlet-title>.caption,
    .portlet.solid.blue-hoki>.portlet-title>.caption,
    .portlet.solid.blue-madison>.portlet-title>.caption,
    .portlet.solid.blue-sharp>.portlet-title>.caption,
    .portlet.solid.blue-soft>.portlet-title>.caption,
    .portlet.solid.blue-steel>.portlet-title>.caption,
    .portlet.solid.blue>.portlet-title>.caption,
    .portlet.solid.dark>.portlet-title>.caption,
    .portlet.solid.default>.portlet-title>.caption,
    .portlet.solid.green-dark>.portlet-title>.caption,
    .portlet.solid.green-haze>.portlet-title>.caption,
    .portlet.solid.green-jungle>.portlet-title>.caption,
    .portlet.solid.green-meadow>.portlet-title>.caption,
    .portlet.solid.green-seagreen>.portlet-title>.caption,
    .portlet.solid.green-sharp>.portlet-title>.caption,
    .portlet.solid.green-soft>.portlet-title>.caption,
    .portlet.solid.green-turquoise>.portlet-title>.caption,
    .portlet.solid.green>.portlet-title>.caption,
    .portlet.solid.grey-cararra>.portlet-title>.caption,
    .portlet.solid.grey-cascade>.portlet-title>.caption,
    .portlet.solid.grey-gallery>.portlet-title>.caption,
    .portlet.solid.grey-mint>.portlet-title>.caption,
    .portlet.solid.grey-salsa>.portlet-title>.caption,
    .portlet.solid.grey-silver>.portlet-title>.caption,
    .portlet.solid.grey-steel>.portlet-title>.caption,
    .portlet.solid.grey>.portlet-title>.caption,
    .portlet.solid.purple-intense>.portlet-title>.caption,
    .portlet.solid.purple-medium>.portlet-title>.caption,
    .portlet.solid.purple-plum>.portlet-title>.caption,
    .portlet.solid.purple-seance>.portlet-title>.caption,
    .portlet.solid.purple-sharp>.portlet-title>.caption,
    .portlet.solid.purple-soft>.portlet-title>.caption,
    .portlet.solid.purple-studio>.portlet-title>.caption,
    .portlet.solid.purple-wisteria>.portlet-title>.caption,
    .portlet.solid.purple>.portlet-title>.caption,
    .portlet.solid.red-flamingo>.portlet-title>.caption,
    .portlet.solid.red-haze>.portlet-title>.caption,
    .portlet.solid.red-intense>.portlet-title>.caption,
    .portlet.solid.red-mint>.portlet-title>.caption,
    .portlet.solid.red-pink>.portlet-title>.caption,
    .portlet.solid.red-soft>.portlet-title>.caption,
    .portlet.solid.red-sunglo>.portlet-title>.caption,
    .portlet.solid.red-thunderbird>.portlet-title>.caption,
    .portlet.solid.red>.portlet-title>.caption,
    .portlet.solid.white>.portlet-title>.caption,
    .portlet.solid.yellow-casablanca>.portlet-title>.caption,
    .portlet.solid.yellow-crusta>.portlet-title>.caption,
    .portlet.solid.yellow-gold>.portlet-title>.caption,
    .portlet.solid.yellow-haze>.portlet-title>.caption,
    .portlet.solid.yellow-lemon>.portlet-title>.caption,
    .portlet.solid.yellow-mint>.portlet-title>.caption,
    .portlet.solid.yellow-saffron>.portlet-title>.caption,
    .portlet.solid.yellow-soft>.portlet-title>.caption,
    .portlet.solid.yellow>.portlet-title>.caption {
        font-weight: 400
    }

    .portlet.light>.portlet-title>.caption.caption-md>.caption-subject {
        font-size: 15px
    }

    .portlet.light>.portlet-title>.caption.caption-md>i {
        font-size: 14px
    }

    .portlet.light>.portlet-title>.actions {
        padding: 6px 0 14px
    }

    .portlet.light>.portlet-title>.actions .btn-default {
        color: #666
    }

    .portlet.light>.portlet-title>.actions .btn-icon-only {
        height: 27px;
        width: 27px
    }

    .portlet.light>.portlet-title>.actions .dropdown-menu li>a {
        color: #555
    }

    .portlet.light>.portlet-title>.inputs {
        float: right;
        display: inline-block;
        padding: 4px 0
    }

    .portlet.light>.portlet-title>.inputs>.portlet-input .input-icon>i {
        font-size: 14px;
        margin-top: 9px
    }

    .portlet.light>.portlet-title>.inputs>.portlet-input .input-icon>.form-control {
        height: 30px;
        padding: 2px 26px 3px 10px;
        font-size: 13px
    }

    .portlet.light>.portlet-title>.inputs>.portlet-input>.form-control {
        height: 30px;
        padding: 3px 10px;
        font-size: 13px
    }

    .portlet.light>.portlet-title>.pagination {
        padding: 2px 0 13px
    }

    .portlet.light>.portlet-title>.tools {
        padding: 10px 0 13px;
        margin-top: 2px
    }

    .portlet.light>.portlet-title>.nav-tabs>li {
        margin: 0;
        padding: 0
    }

    .portlet.light>.portlet-title>.nav-tabs>li>a {
        margin: 0;
        padding: 12px 13px 13px;
        font-size: 13px;
        color: #666
    }

    .portlet.light>.portlet-title>.nav-tabs>li.active>a,
    .portlet.light>.portlet-title>.nav-tabs>li:hover>a {
        margin: 0;
        background: 0 0;
        color: #333
    }

    .portlet.light.form-fit {
        padding: 0
    }

    .portlet.light.form-fit>.portlet-title {
        padding: 17px 20px 10px;
        margin-bottom: 0
    }

    .portlet.light .portlet-body {
        padding-top: 8px
    }

    .portlet.light.portlet-fullscreen>.portlet-body {
        padding: 8px 0
    }

    .portlet.light.portlet-fit {
        padding: 0
    }

    .portlet.light.portlet-fit>.portlet-title {
        padding: 15px 20px 10px
    }

    .portlet.light.portlet-fit>.portlet-body {
        padding: 10px 20px 20px
    }

    .portlet.light.portlet-fit.portlet-form>.portlet-body {
        padding: 0
    }

    .portlet.light.portlet-fit.portlet-form>.portlet-body .form-actions {
        background: 0 0
    }

    .portlet.box.white>.portlet-title,
    .portlet.white,
    .portlet>.portlet-body.white {
        background-color: #fff
    }

    .portlet.light.portlet-datatable.portlet-fit>.portlet-body {
        padding-top: 10px;
        padding-bottom: 25px
    }

    .tab-pane>p:last-child {
        margin-bottom: 0
    }

    .tabs-reversed>li {
        float: right;
        margin-right: 0
    }

    .tabs-reversed>li>a {
        margin-right: 0
    }

    .portlet-sortable-placeholder {
        border: 2px dashed #eee;
        margin-bottom: 25px
    }

    .portlet-sortable-empty {
        box-shadow: none !important;
        height: 45px
    }

    .portlet-collapsed {
        display: none
    }

    @media (max-width: 991px) {
        .portlet-collapsed-on-mobile {
            display: none
        }
    }

    .portlet.solid.white>.portlet-body,
    .portlet.solid.white>.portlet-title {
        border: 0;
        color: #666
    }

    .portlet.solid.white>.portlet-title>.caption>i {
        color: #666
    }

    .portlet.solid.white>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.white>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.white>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.white>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.white>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.white>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.white {
        border: 1px solid #fff;
        border-top: 0
    }

    .portlet.box.white>.portlet-title>.caption,
    .portlet.box.white>.portlet-title>.caption>i {
        color: #666
    }

    .portlet.box.white>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #fff;
        color: #fff
    }

    .portlet.box.default>.portlet-title,
    .portlet.default,
    .portlet>.portlet-body.default {
        background-color: #e1e5ec
    }

    .portlet.box.white>.portlet-title>.actions .btn-default>i {
        color: #fff
    }

    .portlet.box.white>.portlet-title>.actions .btn-default.active,
    .portlet.box.white>.portlet-title>.actions .btn-default:active,
    .portlet.box.white>.portlet-title>.actions .btn-default:focus,
    .portlet.box.white>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #fff;
        color: #fff
    }

    .portlet.solid.default>.portlet-body,
    .portlet.solid.default>.portlet-title {
        border: 0;
        color: #666
    }

    .portlet.solid.default>.portlet-title>.caption>i {
        color: #666
    }

    .portlet.solid.default>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.default>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.default>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.default>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.default>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.default>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.default {
        border: 1px solid #fff;
        border-top: 0
    }

    .portlet.box.default>.portlet-title>.caption,
    .portlet.box.default>.portlet-title>.caption>i {
        color: #666
    }

    .portlet.box.default>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #fff;
        color: #fff
    }

    .portlet.box.dark>.portlet-title,
    .portlet.dark,
    .portlet>.portlet-body.dark {
        background-color: #2f353b
    }

    .portlet.box.default>.portlet-title>.actions .btn-default>i {
        color: #fff
    }

    .portlet.box.default>.portlet-title>.actions .btn-default.active,
    .portlet.box.default>.portlet-title>.actions .btn-default:active,
    .portlet.box.default>.portlet-title>.actions .btn-default:focus,
    .portlet.box.default>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #fff;
        color: #fff
    }

    .portlet.solid.dark>.portlet-body,
    .portlet.solid.dark>.portlet-title {
        border: 0;
        color: #FFF
    }

    .portlet.solid.dark>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.solid.dark>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.dark>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.dark>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.dark>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.dark>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.dark>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.dark {
        border: 1px solid #464f57;
        border-top: 0
    }

    .portlet.box.dark>.portlet-title>.caption,
    .portlet.box.dark>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.box.dark>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #616d79;
        color: #6c7a88
    }

    .portlet.blue,
    .portlet.box.blue>.portlet-title,
    .portlet>.portlet-body.blue {
        background-color: #3598dc
    }

    .portlet.box.dark>.portlet-title>.actions .btn-default>i {
        color: #738290
    }

    .portlet.box.dark>.portlet-title>.actions .btn-default.active,
    .portlet.box.dark>.portlet-title>.actions .btn-default:active,
    .portlet.box.dark>.portlet-title>.actions .btn-default:focus,
    .portlet.box.dark>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #798794;
        color: #8793a0
    }

    .portlet.solid.blue>.portlet-body,
    .portlet.solid.blue>.portlet-title {
        border: 0;
        color: #FFF
    }

    .portlet.solid.blue>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.solid.blue>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.blue>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.blue>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.blue>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.blue>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.blue>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.blue {
        border: 1px solid #60aee4;
        border-top: 0
    }

    .portlet.box.blue>.portlet-title>.caption,
    .portlet.box.blue>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.box.blue>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #95c9ed;
        color: #aad4f0
    }

    .portlet.blue-madison,
    .portlet.box.blue-madison>.portlet-title,
    .portlet>.portlet-body.blue-madison {
        background-color: #578ebe
    }

    .portlet.box.blue>.portlet-title>.actions .btn-default>i {
        color: #b7daf3
    }

    .portlet.box.blue>.portlet-title>.actions .btn-default.active,
    .portlet.box.blue>.portlet-title>.actions .btn-default:active,
    .portlet.box.blue>.portlet-title>.actions .btn-default:focus,
    .portlet.box.blue>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #c0dff4;
        color: #d6eaf8
    }

    .portlet.solid.blue-madison>.portlet-body,
    .portlet.solid.blue-madison>.portlet-title {
        border: 0;
        color: #FFF
    }

    .portlet.solid.blue-madison>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.solid.blue-madison>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.blue-madison>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.blue-madison>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.blue-madison>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.blue-madison>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.blue-madison>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.blue-madison {
        border: 1px solid #7ca7cc;
        border-top: 0
    }

    .portlet.box.blue-madison>.portlet-title>.caption,
    .portlet.box.blue-madison>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.box.blue-madison>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #a8c4dd;
        color: #bad1e4
    }

    .portlet.blue-chambray,
    .portlet.box.blue-chambray>.portlet-title,
    .portlet>.portlet-body.blue-chambray {
        background-color: #2C3E50
    }

    .portlet.box.blue-madison>.portlet-title>.actions .btn-default>i {
        color: #c5d8e9
    }

    .portlet.box.blue-madison>.portlet-title>.actions .btn-default.active,
    .portlet.box.blue-madison>.portlet-title>.actions .btn-default:active,
    .portlet.box.blue-madison>.portlet-title>.actions .btn-default:focus,
    .portlet.box.blue-madison>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #cdddec;
        color: #dfeaf3
    }

    .portlet.solid.blue-chambray>.portlet-body,
    .portlet.solid.blue-chambray>.portlet-title {
        border: 0;
        color: #FFF
    }

    .portlet.solid.blue-chambray>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.solid.blue-chambray>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.blue-chambray>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.blue-chambray>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.blue-chambray>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.blue-chambray>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.blue-chambray>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.blue-chambray {
        border: 1px solid #3e5871;
        border-top: 0
    }

    .portlet.box.blue-chambray>.portlet-title>.caption,
    .portlet.box.blue-chambray>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.box.blue-chambray>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #547698;
        color: #5f83a7
    }

    .portlet.blue-ebonyclay,
    .portlet.box.blue-ebonyclay>.portlet-title,
    .portlet>.portlet-body.blue-ebonyclay {
        background-color: #22313F
    }

    .portlet.box.blue-chambray>.portlet-title>.actions .btn-default>i {
        color: #698bac
    }

    .portlet.box.blue-chambray>.portlet-title>.actions .btn-default.active,
    .portlet.box.blue-chambray>.portlet-title>.actions .btn-default:active,
    .portlet.box.blue-chambray>.portlet-title>.actions .btn-default:focus,
    .portlet.box.blue-chambray>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #6f90b0;
        color: #809cb9
    }

    .portlet.solid.blue-ebonyclay>.portlet-body,
    .portlet.solid.blue-ebonyclay>.portlet-title {
        border: 0;
        color: #FFF
    }

    .portlet.solid.blue-ebonyclay>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.solid.blue-ebonyclay>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.blue-ebonyclay>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.blue-ebonyclay>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.blue-ebonyclay>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.blue-ebonyclay>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.blue-ebonyclay>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.blue-ebonyclay {
        border: 1px solid #344b60;
        border-top: 0
    }

    .portlet.box.blue-ebonyclay>.portlet-title>.caption,
    .portlet.box.blue-ebonyclay>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.box.blue-ebonyclay>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #496a88;
        color: #527798
    }

    .portlet.blue-hoki,
    .portlet.box.blue-hoki>.portlet-title,
    .portlet>.portlet-body.blue-hoki {
        background-color: #67809F
    }

    .portlet.box.blue-ebonyclay>.portlet-title>.actions .btn-default>i {
        color: #587ea2
    }

    .portlet.box.blue-ebonyclay>.portlet-title>.actions .btn-default.active,
    .portlet.box.blue-ebonyclay>.portlet-title>.actions .btn-default:active,
    .portlet.box.blue-ebonyclay>.portlet-title>.actions .btn-default:focus,
    .portlet.box.blue-ebonyclay>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #5d83a7;
        color: #6d90b0
    }

    .portlet.solid.blue-hoki>.portlet-body,
    .portlet.solid.blue-hoki>.portlet-title {
        border: 0;
        color: #FFF
    }

    .portlet.solid.blue-hoki>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.solid.blue-hoki>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.blue-hoki>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.blue-hoki>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.blue-hoki>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.blue-hoki>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.blue-hoki>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.blue-hoki {
        border: 1px solid #869ab3;
        border-top: 0
    }

    .portlet.box.blue-hoki>.portlet-title>.caption,
    .portlet.box.blue-hoki>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.box.blue-hoki>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #acb9ca;
        color: #bbc7d4
    }

    .portlet.blue-steel,
    .portlet.box.blue-steel>.portlet-title,
    .portlet>.portlet-body.blue-steel {
        background-color: #4B77BE
    }

    .portlet.box.blue-hoki>.portlet-title>.actions .btn-default>i {
        color: #c5ceda
    }

    .portlet.box.blue-hoki>.portlet-title>.actions .btn-default.active,
    .portlet.box.blue-hoki>.portlet-title>.actions .btn-default:active,
    .portlet.box.blue-hoki>.portlet-title>.actions .btn-default:focus,
    .portlet.box.blue-hoki>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #cbd4de;
        color: #dbe1e8
    }

    .portlet.solid.blue-steel>.portlet-body,
    .portlet.solid.blue-steel>.portlet-title {
        border: 0;
        color: #FFF
    }

    .portlet.solid.blue-steel>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.solid.blue-steel>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.blue-steel>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.blue-steel>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.blue-steel>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.blue-steel>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.blue-steel>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.blue-steel {
        border: 1px solid #7093cc;
        border-top: 0
    }

    .portlet.box.blue-steel>.portlet-title>.caption,
    .portlet.box.blue-steel>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.box.blue-steel>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #9db5dc;
        color: #b0c3e3
    }

    .portlet.blue-soft,
    .portlet.box.blue-soft>.portlet-title,
    .portlet>.portlet-body.blue-soft {
        background-color: #4c87b9
    }

    .portlet.box.blue-steel>.portlet-title>.actions .btn-default>i {
        color: #bbcce7
    }

    .portlet.box.blue-steel>.portlet-title>.actions .btn-default.active,
    .portlet.box.blue-steel>.portlet-title>.actions .btn-default:active,
    .portlet.box.blue-steel>.portlet-title>.actions .btn-default:focus,
    .portlet.box.blue-steel>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #c3d2e9;
        color: #d6e0f0
    }

    .portlet.solid.blue-soft>.portlet-body,
    .portlet.solid.blue-soft>.portlet-title {
        border: 0;
        color: #FFF
    }

    .portlet.solid.blue-soft>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.solid.blue-soft>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.blue-soft>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.blue-soft>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.blue-soft>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.blue-soft>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.blue-soft>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.blue-soft {
        border: 1px solid #71a0c7;
        border-top: 0
    }

    .portlet.box.blue-soft>.portlet-title>.caption,
    .portlet.box.blue-soft>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.box.blue-soft>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #9dbdd9;
        color: #afc9e0
    }

    .portlet.blue-dark,
    .portlet.box.blue-dark>.portlet-title,
    .portlet>.portlet-body.blue-dark {
        background-color: #5e738b
    }

    .portlet.box.blue-soft>.portlet-title>.actions .btn-default>i {
        color: #bad1e4
    }

    .portlet.box.blue-soft>.portlet-title>.actions .btn-default.active,
    .portlet.box.blue-soft>.portlet-title>.actions .btn-default:active,
    .portlet.box.blue-soft>.portlet-title>.actions .btn-default:focus,
    .portlet.box.blue-soft>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #c1d6e7;
        color: #d4e2ee
    }

    .portlet.solid.blue-dark>.portlet-body,
    .portlet.solid.blue-dark>.portlet-title {
        border: 0;
        color: #FFF
    }

    .portlet.solid.blue-dark>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.solid.blue-dark>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.blue-dark>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.blue-dark>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.blue-dark>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.blue-dark>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.blue-dark>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.blue-dark {
        border: 1px solid #788da4;
        border-top: 0
    }

    .portlet.box.blue-dark>.portlet-title>.caption,
    .portlet.box.blue-dark>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.box.blue-dark>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #9dacbd;
        color: #acb8c7
    }

    .portlet.blue-sharp,
    .portlet.box.blue-sharp>.portlet-title,
    .portlet>.portlet-body.blue-sharp {
        background-color: #5C9BD1
    }

    .portlet.box.blue-dark>.portlet-title>.actions .btn-default>i {
        color: #b5c0cd
    }

    .portlet.box.blue-dark>.portlet-title>.actions .btn-default.active,
    .portlet.box.blue-dark>.portlet-title>.actions .btn-default:active,
    .portlet.box.blue-dark>.portlet-title>.actions .btn-default:focus,
    .portlet.box.blue-dark>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #bbc5d1;
        color: #cad2db
    }

    .portlet.solid.blue-sharp>.portlet-body,
    .portlet.solid.blue-sharp>.portlet-title {
        border: 0;
        color: #FFF
    }

    .portlet.solid.blue-sharp>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.solid.blue-sharp>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.blue-sharp>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.blue-sharp>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.blue-sharp>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.blue-sharp>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.blue-sharp>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.blue-sharp {
        border: 1px solid #84b3dc;
        border-top: 0
    }

    .portlet.box.blue-sharp>.portlet-title>.caption,
    .portlet.box.blue-sharp>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.box.blue-sharp>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #b4d1ea;
        color: #c7ddef
    }

    .portlet.box.green>.portlet-title,
    .portlet.green,
    .portlet>.portlet-body.green {
        background-color: #32c5d2
    }

    .portlet.box.blue-sharp>.portlet-title>.actions .btn-default>i {
        color: #d3e4f3
    }

    .portlet.box.blue-sharp>.portlet-title>.actions .btn-default.active,
    .portlet.box.blue-sharp>.portlet-title>.actions .btn-default:active,
    .portlet.box.blue-sharp>.portlet-title>.actions .btn-default:focus,
    .portlet.box.blue-sharp>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #dbe9f5;
        color: #eff5fb
    }

    .portlet.solid.green>.portlet-body,
    .portlet.solid.green>.portlet-title {
        border: 0;
        color: #FFF
    }

    .portlet.solid.green>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.solid.green>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.green>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.green>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.green>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.green>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.green>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.green {
        border: 1px solid #5cd1db;
        border-top: 0
    }

    .portlet.box.green>.portlet-title>.caption,
    .portlet.box.green>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.box.green>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #8edfe6;
        color: #a3e5eb
    }

    .portlet.box.green-meadow>.portlet-title,
    .portlet.green-meadow,
    .portlet>.portlet-body.green-meadow {
        background-color: #1BBC9B
    }

    .portlet.box.green>.portlet-title>.actions .btn-default>i {
        color: #afe8ee
    }

    .portlet.box.green>.portlet-title>.actions .btn-default.active,
    .portlet.box.green>.portlet-title>.actions .btn-default:active,
    .portlet.box.green>.portlet-title>.actions .btn-default:focus,
    .portlet.box.green>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #b8ebef;
        color: #cdf1f4
    }

    .portlet.solid.green-meadow>.portlet-body,
    .portlet.solid.green-meadow>.portlet-title {
        border: 0;
        color: #FFF
    }

    .portlet.solid.green-meadow>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.solid.green-meadow>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.green-meadow>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.green-meadow>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.green-meadow>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.green-meadow>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.green-meadow>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.green-meadow {
        border: 1px solid #2ae0bb;
        border-top: 0
    }

    .portlet.box.green-meadow>.portlet-title>.caption,
    .portlet.box.green-meadow>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.box.green-meadow>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #5fe8cc;
        color: #75ebd3
    }

    .portlet.box.green-seagreen>.portlet-title,
    .portlet.green-seagreen,
    .portlet>.portlet-body.green-seagreen {
        background-color: #1BA39C
    }

    .portlet.box.green-meadow>.portlet-title>.actions .btn-default>i {
        color: #83edd7
    }

    .portlet.box.green-meadow>.portlet-title>.actions .btn-default.active,
    .portlet.box.green-meadow>.portlet-title>.actions .btn-default:active,
    .portlet.box.green-meadow>.portlet-title>.actions .btn-default:focus,
    .portlet.box.green-meadow>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #8ceeda;
        color: #a2f2e1
    }

    .portlet.solid.green-seagreen>.portlet-body,
    .portlet.solid.green-seagreen>.portlet-title {
        border: 0;
        color: #FFF
    }

    .portlet.solid.green-seagreen>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.solid.green-seagreen>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.green-seagreen>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.green-seagreen>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.green-seagreen>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.green-seagreen>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.green-seagreen>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.green-seagreen {
        border: 1px solid #22cfc6;
        border-top: 0
    }

    .portlet.box.green-seagreen>.portlet-title>.caption,
    .portlet.box.green-seagreen>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.box.green-seagreen>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #4de1da;
        color: #63e5de
    }

    .portlet.box.green-turquoise>.portlet-title,
    .portlet.green-turquoise,
    .portlet>.portlet-body.green-turquoise {
        background-color: #36D7B7
    }

    .portlet.box.green-seagreen>.portlet-title>.actions .btn-default>i {
        color: #70e7e1
    }

    .portlet.box.green-seagreen>.portlet-title>.actions .btn-default.active,
    .portlet.box.green-seagreen>.portlet-title>.actions .btn-default:active,
    .portlet.box.green-seagreen>.portlet-title>.actions .btn-default:focus,
    .portlet.box.green-seagreen>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #78e9e3;
        color: #8eece8
    }

    .portlet.solid.green-turquoise>.portlet-body,
    .portlet.solid.green-turquoise>.portlet-title {
        border: 0;
        color: #FFF
    }

    .portlet.solid.green-turquoise>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.solid.green-turquoise>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.green-turquoise>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.green-turquoise>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.green-turquoise>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.green-turquoise>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.green-turquoise>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.green-turquoise {
        border: 1px solid #61dfc6;
        border-top: 0
    }

    .portlet.box.green-turquoise>.portlet-title>.caption,
    .portlet.box.green-turquoise>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.box.green-turquoise>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #94ead9;
        color: #a9eee0
    }

    .portlet.box.green-haze>.portlet-title,
    .portlet.green-haze,
    .portlet>.portlet-body.green-haze {
        background-color: #44b6ae
    }

    .portlet.box.green-turquoise>.portlet-title>.actions .btn-default>i {
        color: #b6f0e5
    }

    .portlet.box.green-turquoise>.portlet-title>.actions .btn-default.active,
    .portlet.box.green-turquoise>.portlet-title>.actions .btn-default:active,
    .portlet.box.green-turquoise>.portlet-title>.actions .btn-default:focus,
    .portlet.box.green-turquoise>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #bef2e8;
        color: #d3f6ef
    }

    .portlet.solid.green-haze>.portlet-body,
    .portlet.solid.green-haze>.portlet-title {
        border: 0;
        color: #FFF
    }

    .portlet.solid.green-haze>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.solid.green-haze>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.green-haze>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.green-haze>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.green-haze>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.green-haze>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.green-haze>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.green-haze {
        border: 1px solid #67c6bf;
        border-top: 0
    }

    .portlet.box.green-haze>.portlet-title>.caption,
    .portlet.box.green-haze>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.box.green-haze>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #93d7d2;
        color: #a6deda
    }

    .portlet.box.green-jungle>.portlet-title,
    .portlet.green-jungle,
    .portlet>.portlet-body.green-jungle {
        background-color: #26C281
    }

    .portlet.box.green-haze>.portlet-title>.actions .btn-default>i {
        color: #b1e2de
    }

    .portlet.box.green-haze>.portlet-title>.actions .btn-default.active,
    .portlet.box.green-haze>.portlet-title>.actions .btn-default:active,
    .portlet.box.green-haze>.portlet-title>.actions .btn-default:focus,
    .portlet.box.green-haze>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #b9e5e2;
        color: #cbece9
    }

    .portlet.solid.green-jungle>.portlet-body,
    .portlet.solid.green-jungle>.portlet-title {
        border: 0;
        color: #FFF
    }

    .portlet.solid.green-jungle>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.solid.green-jungle>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.green-jungle>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.green-jungle>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.green-jungle>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.green-jungle>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.green-jungle>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.green-jungle {
        border: 1px solid #41da9a;
        border-top: 0
    }

    .portlet.box.green-jungle>.portlet-title>.caption,
    .portlet.box.green-jungle>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.box.green-jungle>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #74e4b5;
        color: #8ae8c1
    }

    .portlet.box.green-soft>.portlet-title,
    .portlet.green-soft,
    .portlet>.portlet-body.green-soft {
        background-color: #3faba4
    }

    .portlet.box.green-jungle>.portlet-title>.actions .btn-default>i {
        color: #96ebc8
    }

    .portlet.box.green-jungle>.portlet-title>.actions .btn-default.active,
    .portlet.box.green-jungle>.portlet-title>.actions .btn-default:active,
    .portlet.box.green-jungle>.portlet-title>.actions .btn-default:focus,
    .portlet.box.green-jungle>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #9feccc;
        color: #b4f0d7
    }

    .portlet.solid.green-soft>.portlet-body,
    .portlet.solid.green-soft>.portlet-title {
        border: 0;
        color: #FFF
    }

    .portlet.solid.green-soft>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.solid.green-soft>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.green-soft>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.green-soft>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.green-soft>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.green-soft>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.green-soft>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.green-soft {
        border: 1px solid #5bc2bc;
        border-top: 0
    }

    .portlet.box.green-soft>.portlet-title>.caption,
    .portlet.box.green-soft>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.box.green-soft>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #87d3ce;
        color: #9adad6
    }

    .portlet.box.green-dark>.portlet-title,
    .portlet.green-dark,
    .portlet>.portlet-body.green-dark {
        background-color: #4DB3A2
    }

    .portlet.box.green-soft>.portlet-title>.actions .btn-default>i {
        color: #a5deda
    }

    .portlet.box.green-soft>.portlet-title>.actions .btn-default.active,
    .portlet.box.green-soft>.portlet-title>.actions .btn-default:active,
    .portlet.box.green-soft>.portlet-title>.actions .btn-default:focus,
    .portlet.box.green-soft>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #ade1dd;
        color: #bfe7e5
    }

    .portlet.solid.green-dark>.portlet-body,
    .portlet.solid.green-dark>.portlet-title {
        border: 0;
        color: #FFF
    }

    .portlet.solid.green-dark>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.solid.green-dark>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.green-dark>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.green-dark>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.green-dark>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.green-dark>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.green-dark>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.green-dark {
        border: 1px solid #71c2b5;
        border-top: 0
    }

    .portlet.box.green-dark>.portlet-title>.caption,
    .portlet.box.green-dark>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.box.green-dark>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #9cd5cb;
        color: #addcd4
    }

    .portlet.box.green-sharp>.portlet-title,
    .portlet.green-sharp,
    .portlet>.portlet-body.green-sharp {
        background-color: #2ab4c0
    }

    .portlet.box.green-dark>.portlet-title>.actions .btn-default>i {
        color: #b8e1da
    }

    .portlet.box.green-dark>.portlet-title>.actions .btn-default.active,
    .portlet.box.green-dark>.portlet-title>.actions .btn-default:active,
    .portlet.box.green-dark>.portlet-title>.actions .btn-default:focus,
    .portlet.box.green-dark>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #bfe4de;
        color: #d1ebe7
    }

    .portlet.solid.green-sharp>.portlet-body,
    .portlet.solid.green-sharp>.portlet-title {
        border: 0;
        color: #FFF
    }

    .portlet.solid.green-sharp>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.solid.green-sharp>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.green-sharp>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.green-sharp>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.green-sharp>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.green-sharp>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.green-sharp>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.green-sharp {
        border: 1px solid #46cbd7;
        border-top: 0
    }

    .portlet.box.green-sharp>.portlet-title>.caption,
    .portlet.box.green-sharp>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.box.green-sharp>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #79d9e2;
        color: #8edfe6
    }

    .portlet.box.grey>.portlet-title,
    .portlet.grey,
    .portlet>.portlet-body.grey {
        background-color: #E5E5E5
    }

    .portlet.box.green-sharp>.portlet-title>.actions .btn-default>i {
        color: #9ae3e9
    }

    .portlet.box.green-sharp>.portlet-title>.actions .btn-default.active,
    .portlet.box.green-sharp>.portlet-title>.actions .btn-default:active,
    .portlet.box.green-sharp>.portlet-title>.actions .btn-default:focus,
    .portlet.box.green-sharp>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #a2e5eb;
        color: #b7ebef
    }

    .portlet.solid.grey>.portlet-body,
    .portlet.solid.grey>.portlet-title {
        border: 0;
        color: #333
    }

    .portlet.solid.grey>.portlet-title>.caption>i {
        color: #333
    }

    .portlet.solid.grey>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.grey>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.grey>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.grey>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.grey>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.grey>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.grey {
        border: 1px solid #fff;
        border-top: 0
    }

    .portlet.box.grey>.portlet-title>.caption,
    .portlet.box.grey>.portlet-title>.caption>i {
        color: #333
    }

    .portlet.box.grey>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #fff;
        color: #fff
    }

    .portlet.box.grey-steel>.portlet-title,
    .portlet.grey-steel,
    .portlet>.portlet-body.grey-steel {
        background-color: #e9edef
    }

    .portlet.box.grey>.portlet-title>.actions .btn-default>i {
        color: #fff
    }

    .portlet.box.grey>.portlet-title>.actions .btn-default.active,
    .portlet.box.grey>.portlet-title>.actions .btn-default:active,
    .portlet.box.grey>.portlet-title>.actions .btn-default:focus,
    .portlet.box.grey>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #fff;
        color: #fff
    }

    .portlet.solid.grey-steel>.portlet-body,
    .portlet.solid.grey-steel>.portlet-title {
        border: 0;
        color: #80898e
    }

    .portlet.solid.grey-steel>.portlet-title>.caption>i {
        color: #80898e
    }

    .portlet.solid.grey-steel>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.grey-steel>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.grey-steel>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.grey-steel>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.grey-steel>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.grey-steel>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.grey-steel {
        border: 1px solid #fff;
        border-top: 0
    }

    .portlet.box.grey-steel>.portlet-title>.caption,
    .portlet.box.grey-steel>.portlet-title>.caption>i {
        color: #80898e
    }

    .portlet.box.grey-steel>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #fff;
        color: #fff
    }

    .portlet.box.grey-cararra>.portlet-title,
    .portlet.grey-cararra,
    .portlet>.portlet-body.grey-cararra {
        background-color: #fafafa
    }

    .portlet.box.grey-steel>.portlet-title>.actions .btn-default>i {
        color: #fff
    }

    .portlet.box.grey-steel>.portlet-title>.actions .btn-default.active,
    .portlet.box.grey-steel>.portlet-title>.actions .btn-default:active,
    .portlet.box.grey-steel>.portlet-title>.actions .btn-default:focus,
    .portlet.box.grey-steel>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #fff;
        color: #fff
    }

    .portlet.solid.grey-cararra>.portlet-body,
    .portlet.solid.grey-cararra>.portlet-title {
        border: 0;
        color: #333
    }

    .portlet.solid.grey-cararra>.portlet-title>.caption>i {
        color: #333
    }

    .portlet.solid.grey-cararra>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.grey-cararra>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.grey-cararra>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.grey-cararra>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.grey-cararra>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.grey-cararra>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.grey-cararra {
        border: 1px solid #fff;
        border-top: 0
    }

    .portlet.box.grey-cararra>.portlet-title>.caption,
    .portlet.box.grey-cararra>.portlet-title>.caption>i {
        color: #333
    }

    .portlet.box.grey-cararra>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #fff;
        color: #fff
    }

    .portlet.box.grey-gallery>.portlet-title,
    .portlet.grey-gallery,
    .portlet>.portlet-body.grey-gallery {
        background-color: #555
    }

    .portlet.box.grey-cararra>.portlet-title>.actions .btn-default>i {
        color: #fff
    }

    .portlet.box.grey-cararra>.portlet-title>.actions .btn-default.active,
    .portlet.box.grey-cararra>.portlet-title>.actions .btn-default:active,
    .portlet.box.grey-cararra>.portlet-title>.actions .btn-default:focus,
    .portlet.box.grey-cararra>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #fff;
        color: #fff
    }

    .portlet.solid.grey-gallery>.portlet-body,
    .portlet.solid.grey-gallery>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.grey-gallery>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.grey-gallery>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.grey-gallery>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.grey-gallery>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.grey-gallery>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.grey-gallery>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.grey-gallery>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.grey-gallery {
        border: 1px solid #6f6f6f;
        border-top: 0
    }

    .portlet.box.grey-gallery>.portlet-title>.caption,
    .portlet.box.grey-gallery>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.grey-gallery>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #8d8d8d;
        color: #9a9a9a
    }

    .portlet.box.grey-cascade>.portlet-title,
    .portlet.grey-cascade,
    .portlet>.portlet-body.grey-cascade {
        background-color: #95A5A6
    }

    .portlet.box.grey-gallery>.portlet-title>.actions .btn-default>i {
        color: #a2a2a2
    }

    .portlet.box.grey-gallery>.portlet-title>.actions .btn-default.active,
    .portlet.box.grey-gallery>.portlet-title>.actions .btn-default:active,
    .portlet.box.grey-gallery>.portlet-title>.actions .btn-default:focus,
    .portlet.box.grey-gallery>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #a7a7a7;
        color: #b3b3b3
    }

    .portlet.solid.grey-cascade>.portlet-body,
    .portlet.solid.grey-cascade>.portlet-title {
        border: 0;
        color: #FFF
    }

    .portlet.solid.grey-cascade>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.solid.grey-cascade>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.grey-cascade>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.grey-cascade>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.grey-cascade>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.grey-cascade>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.grey-cascade>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.grey-cascade {
        border: 1px solid #b1bdbd;
        border-top: 0
    }

    .portlet.box.grey-cascade>.portlet-title>.caption,
    .portlet.box.grey-cascade>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.box.grey-cascade>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #d2d9d9;
        color: #e0e5e5
    }

    .portlet.box.grey-silver>.portlet-title,
    .portlet.grey-silver,
    .portlet>.portlet-body.grey-silver {
        background-color: #BFBFBF
    }

    .portlet.box.grey-cascade>.portlet-title>.actions .btn-default>i {
        color: #e8ecec
    }

    .portlet.box.grey-cascade>.portlet-title>.actions .btn-default.active,
    .portlet.box.grey-cascade>.portlet-title>.actions .btn-default:active,
    .portlet.box.grey-cascade>.portlet-title>.actions .btn-default:focus,
    .portlet.box.grey-cascade>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #eef0f0;
        color: #fcfcfc
    }

    .portlet.solid.grey-silver>.portlet-body,
    .portlet.solid.grey-silver>.portlet-title {
        border: 0;
        color: #FAFCFB
    }

    .portlet.solid.grey-silver>.portlet-title>.caption>i {
        color: #FAFCFB
    }

    .portlet.solid.grey-silver>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.grey-silver>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.grey-silver>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.grey-silver>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.grey-silver>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.grey-silver>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.grey-silver {
        border: 1px solid #d9d9d9;
        border-top: 0
    }

    .portlet.box.grey-silver>.portlet-title>.caption,
    .portlet.box.grey-silver>.portlet-title>.caption>i {
        color: #FAFCFB
    }

    .portlet.box.grey-silver>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #f7f7f7;
        color: #fff
    }

    .portlet.box.grey-salsa>.portlet-title,
    .portlet.grey-salsa,
    .portlet>.portlet-body.grey-salsa {
        background-color: #ACB5C3
    }

    .portlet.box.grey-silver>.portlet-title>.actions .btn-default>i {
        color: #fff
    }

    .portlet.box.grey-silver>.portlet-title>.actions .btn-default.active,
    .portlet.box.grey-silver>.portlet-title>.actions .btn-default:active,
    .portlet.box.grey-silver>.portlet-title>.actions .btn-default:focus,
    .portlet.box.grey-silver>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #fff;
        color: #fff
    }

    .portlet.solid.grey-salsa>.portlet-body,
    .portlet.solid.grey-salsa>.portlet-title {
        border: 0;
        color: #FAFCFB
    }

    .portlet.solid.grey-salsa>.portlet-title>.caption>i {
        color: #FAFCFB
    }

    .portlet.solid.grey-salsa>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.grey-salsa>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.grey-salsa>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.grey-salsa>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.grey-salsa>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.grey-salsa>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.grey-salsa {
        border: 1px solid #cacfd8;
        border-top: 0
    }

    .portlet.box.grey-salsa>.portlet-title>.caption,
    .portlet.box.grey-salsa>.portlet-title>.caption>i {
        color: #FAFCFB
    }

    .portlet.box.grey-salsa>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #edeff2;
        color: #fcfcfd
    }

    .portlet.box.grey-salt>.portlet-title,
    .portlet.grey-salt,
    .portlet>.portlet-body.grey-salt {
        background-color: #bfcad1
    }

    .portlet.box.grey-salsa>.portlet-title>.actions .btn-default>i {
        color: #fff
    }

    .portlet.box.grey-salsa>.portlet-title>.actions .btn-default.active,
    .portlet.box.grey-salsa>.portlet-title>.actions .btn-default:active,
    .portlet.box.grey-salsa>.portlet-title>.actions .btn-default:focus,
    .portlet.box.grey-salsa>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #fff;
        color: #fff
    }

    .portlet.solid.grey-salt>.portlet-body,
    .portlet.solid.grey-salt>.portlet-title {
        border: 0;
        color: #FAFCFB
    }

    .portlet.solid.grey-salt>.portlet-title>.caption {
        font-weight: 400
    }

    .portlet.solid.grey-salt>.portlet-title>.caption>i {
        color: #FAFCFB
    }

    .portlet.solid.grey-salt>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.grey-salt>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.grey-salt>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.grey-salt>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.grey-salt>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.grey-salt>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.grey-salt {
        border: 1px solid #dde3e6;
        border-top: 0
    }

    .portlet.box.grey-salt>.portlet-title>.caption,
    .portlet.box.grey-salt>.portlet-title>.caption>i {
        color: #FAFCFB
    }

    .portlet.box.grey-salt>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #fff;
        color: #fff
    }

    .portlet.box.grey-mint>.portlet-title,
    .portlet.grey-mint,
    .portlet>.portlet-body.grey-mint {
        background-color: #525e64
    }

    .portlet.box.grey-salt>.portlet-title>.actions .btn-default>i {
        color: #fff
    }

    .portlet.box.grey-salt>.portlet-title>.actions .btn-default.active,
    .portlet.box.grey-salt>.portlet-title>.actions .btn-default:active,
    .portlet.box.grey-salt>.portlet-title>.actions .btn-default:focus,
    .portlet.box.grey-salt>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #fff;
        color: #fff
    }

    .portlet.solid.grey-mint>.portlet-body,
    .portlet.solid.grey-mint>.portlet-title {
        border: 0;
        color: #FFF
    }

    .portlet.solid.grey-mint>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.solid.grey-mint>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.grey-mint>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.grey-mint>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.grey-mint>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.grey-mint>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.grey-mint>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.grey-mint {
        border: 1px solid #697880;
        border-top: 0
    }

    .portlet.box.grey-mint>.portlet-title>.caption,
    .portlet.box.grey-mint>.portlet-title>.caption>i {
        color: #FFF
    }

    .portlet.box.grey-mint>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #88979e;
        color: #96a3a9
    }

    .portlet.box.red>.portlet-title,
    .portlet.red,
    .portlet>.portlet-body.red {
        background-color: #e7505a
    }

    .portlet.box.grey-mint>.portlet-title>.actions .btn-default>i {
        color: #9faab0
    }

    .portlet.box.grey-mint>.portlet-title>.actions .btn-default.active,
    .portlet.box.grey-mint>.portlet-title>.actions .btn-default:active,
    .portlet.box.grey-mint>.portlet-title>.actions .btn-default:focus,
    .portlet.box.grey-mint>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #a4afb5;
        color: #b2bcc0
    }

    .portlet.solid.red>.portlet-body,
    .portlet.solid.red>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.red>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.red>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.red>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.red>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.red>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.red>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.red>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.red {
        border: 1px solid #ed7d84;
        border-top: 0
    }

    .portlet.box.red>.portlet-title>.caption,
    .portlet.box.red>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.red>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #f5b3b7;
        color: #f8c9cc
    }

    .portlet.box.red-pink>.portlet-title,
    .portlet.red-pink,
    .portlet>.portlet-body.red-pink {
        background-color: #E08283
    }

    .portlet.box.red>.portlet-title>.actions .btn-default>i {
        color: #f9d7d9
    }

    .portlet.box.red>.portlet-title>.actions .btn-default.active,
    .portlet.box.red>.portlet-title>.actions .btn-default:active,
    .portlet.box.red>.portlet-title>.actions .btn-default:focus,
    .portlet.box.red>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #fbe0e1;
        color: #fef6f6
    }

    .portlet.solid.red-pink>.portlet-body,
    .portlet.solid.red-pink>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.red-pink>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.red-pink>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.red-pink>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.red-pink>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.red-pink>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.red-pink>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.red-pink>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.red-pink {
        border: 1px solid #eaabac;
        border-top: 0
    }

    .portlet.box.red-pink>.portlet-title>.caption,
    .portlet.box.red-pink>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.red-pink>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #f6dcdc;
        color: #fbf0f0
    }

    .portlet.box.red-sunglo>.portlet-title,
    .portlet.red-sunglo,
    .portlet>.portlet-body.red-sunglo {
        background-color: #E26A6A
    }

    .portlet.box.red-pink>.portlet-title>.actions .btn-default>i {
        color: #fefdfd
    }

    .portlet.box.red-pink>.portlet-title>.actions .btn-default.active,
    .portlet.box.red-pink>.portlet-title>.actions .btn-default:active,
    .portlet.box.red-pink>.portlet-title>.actions .btn-default:focus,
    .portlet.box.red-pink>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #fff;
        color: #fff
    }

    .portlet.solid.red-sunglo>.portlet-body,
    .portlet.solid.red-sunglo>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.red-sunglo>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.red-sunglo>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.red-sunglo>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.red-sunglo>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.red-sunglo>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.red-sunglo>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.red-sunglo>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.red-sunglo {
        border: 1px solid #ea9595;
        border-top: 0
    }

    .portlet.box.red-sunglo>.portlet-title>.caption,
    .portlet.box.red-sunglo>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.red-sunglo>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #f4c8c8;
        color: #f8dddd
    }

    .portlet.box.red-intense>.portlet-title,
    .portlet.red-intense,
    .portlet>.portlet-body.red-intense {
        background-color: #e35b5a
    }

    .portlet.box.red-sunglo>.portlet-title>.actions .btn-default>i {
        color: #fbeaea
    }

    .portlet.box.red-sunglo>.portlet-title>.actions .btn-default.active,
    .portlet.box.red-sunglo>.portlet-title>.actions .btn-default:active,
    .portlet.box.red-sunglo>.portlet-title>.actions .btn-default:focus,
    .portlet.box.red-sunglo>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #fdf3f3;
        color: #fff
    }

    .portlet.solid.red-intense>.portlet-body,
    .portlet.solid.red-intense>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.red-intense>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.red-intense>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.red-intense>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.red-intense>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.red-intense>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.red-intense>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.red-intense>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.red-intense {
        border: 1px solid #ea8686;
        border-top: 0
    }

    .portlet.box.red-intense>.portlet-title>.caption,
    .portlet.box.red-intense>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.red-intense>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #f3baba;
        color: #f7d0d0
    }

    .portlet.box.red-thunderbird>.portlet-title,
    .portlet.red-thunderbird,
    .portlet>.portlet-body.red-thunderbird {
        background-color: #D91E18
    }

    .portlet.box.red-intense>.portlet-title>.actions .btn-default>i {
        color: #f9dddd
    }

    .portlet.box.red-intense>.portlet-title>.actions .btn-default.active,
    .portlet.box.red-intense>.portlet-title>.actions .btn-default:active,
    .portlet.box.red-intense>.portlet-title>.actions .btn-default:focus,
    .portlet.box.red-intense>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #fbe6e6;
        color: #fefbfb
    }

    .portlet.solid.red-thunderbird>.portlet-body,
    .portlet.solid.red-thunderbird>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.red-thunderbird>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.red-thunderbird>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.red-thunderbird>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.red-thunderbird>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.red-thunderbird>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.red-thunderbird>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.red-thunderbird>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.red-thunderbird {
        border: 1px solid #e9403b;
        border-top: 0
    }

    .portlet.box.red-thunderbird>.portlet-title>.caption,
    .portlet.box.red-thunderbird>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.red-thunderbird>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #ef7672;
        color: #f28c89
    }

    .portlet.box.red-flamingo>.portlet-title,
    .portlet.red-flamingo,
    .portlet>.portlet-body.red-flamingo {
        background-color: #EF4836
    }

    .portlet.box.red-thunderbird>.portlet-title>.actions .btn-default>i {
        color: #f39997
    }

    .portlet.box.red-thunderbird>.portlet-title>.actions .btn-default.active,
    .portlet.box.red-thunderbird>.portlet-title>.actions .btn-default:active,
    .portlet.box.red-thunderbird>.portlet-title>.actions .btn-default:focus,
    .portlet.box.red-thunderbird>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #f4a2a0;
        color: #f7b9b7
    }

    .portlet.solid.red-flamingo>.portlet-body,
    .portlet.solid.red-flamingo>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.red-flamingo>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.red-flamingo>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.red-flamingo>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.red-flamingo>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.red-flamingo>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.red-flamingo>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.red-flamingo>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.red-flamingo {
        border: 1px solid #f37365;
        border-top: 0
    }

    .portlet.box.red-flamingo>.portlet-title>.caption,
    .portlet.box.red-flamingo>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.red-flamingo>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #f7a79e;
        color: #f9bcb6
    }

    .portlet.box.red-soft>.portlet-title,
    .portlet.red-soft,
    .portlet>.portlet-body.red-soft {
        background-color: #d05454
    }

    .portlet.box.red-flamingo>.portlet-title>.actions .btn-default>i {
        color: #fac9c4
    }

    .portlet.box.red-flamingo>.portlet-title>.actions .btn-default.active,
    .portlet.box.red-flamingo>.portlet-title>.actions .btn-default:active,
    .portlet.box.red-flamingo>.portlet-title>.actions .btn-default:focus,
    .portlet.box.red-flamingo>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #fbd2cd;
        color: #fde7e5
    }

    .portlet.solid.red-soft>.portlet-body,
    .portlet.solid.red-soft>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.red-soft>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.red-soft>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.red-soft>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.red-soft>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.red-soft>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.red-soft>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.red-soft>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.red-soft {
        border: 1px solid #db7c7c;
        border-top: 0
    }

    .portlet.box.red-soft>.portlet-title>.caption,
    .portlet.box.red-soft>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.red-soft>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #e8acac;
        color: #eec0c0
    }

    .portlet.box.red-haze>.portlet-title,
    .portlet.red-haze,
    .portlet>.portlet-body.red-haze {
        background-color: #f36a5a
    }

    .portlet.box.red-soft>.portlet-title>.actions .btn-default>i {
        color: #f1cccc
    }

    .portlet.box.red-soft>.portlet-title>.actions .btn-default.active,
    .portlet.box.red-soft>.portlet-title>.actions .btn-default:active,
    .portlet.box.red-soft>.portlet-title>.actions .btn-default:focus,
    .portlet.box.red-soft>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #f3d4d4;
        color: #f9e8e8
    }

    .portlet.solid.red-haze>.portlet-body,
    .portlet.solid.red-haze>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.red-haze>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.red-haze>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.red-haze>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.red-haze>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.red-haze>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.red-haze>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.red-haze>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.red-haze {
        border: 1px solid #f6958a;
        border-top: 0
    }

    .portlet.box.red-haze>.portlet-title>.caption,
    .portlet.box.red-haze>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.red-haze>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #fbc8c3;
        color: #fcdeda
    }

    .portlet.box.red-mint>.portlet-title,
    .portlet.red-mint,
    .portlet>.portlet-body.red-mint {
        background-color: #e43a45
    }

    .portlet.box.red-haze>.portlet-title>.actions .btn-default>i {
        color: #fdebe9
    }

    .portlet.box.red-haze>.portlet-title>.actions .btn-default.active,
    .portlet.box.red-haze>.portlet-title>.actions .btn-default:active,
    .portlet.box.red-haze>.portlet-title>.actions .btn-default:focus,
    .portlet.box.red-haze>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #fef3f2;
        color: #fff
    }

    .portlet.solid.red-mint>.portlet-body,
    .portlet.solid.red-mint>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.red-mint>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.red-mint>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.red-mint>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.red-mint>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.red-mint>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.red-mint>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.red-mint>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.red-mint {
        border: 1px solid #ea676f;
        border-top: 0
    }

    .portlet.box.red-mint>.portlet-title>.caption,
    .portlet.box.red-mint>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.red-mint>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #f29da2;
        color: #f5b3b7
    }

    .portlet.box.yellow>.portlet-title,
    .portlet.yellow,
    .portlet>.portlet-body.yellow {
        background-color: #c49f47
    }

    .portlet.box.red-mint>.portlet-title>.actions .btn-default>i {
        color: #f6c1c4
    }

    .portlet.box.red-mint>.portlet-title>.actions .btn-default.active,
    .portlet.box.red-mint>.portlet-title>.actions .btn-default:active,
    .portlet.box.red-mint>.portlet-title>.actions .btn-default:focus,
    .portlet.box.red-mint>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #f8cacd;
        color: #fbe0e2
    }

    .portlet.solid.yellow>.portlet-body,
    .portlet.solid.yellow>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.yellow>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.yellow>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.yellow>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.yellow>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.yellow>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.yellow>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.yellow>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.yellow {
        border: 1px solid #d0b36e;
        border-top: 0
    }

    .portlet.box.yellow>.portlet-title>.caption,
    .portlet.box.yellow>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.yellow>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #dfcb9c;
        color: #e5d5af
    }

    .portlet.box.yellow-gold>.portlet-title,
    .portlet.yellow-gold,
    .portlet>.portlet-body.yellow-gold {
        background-color: #E87E04
    }

    .portlet.box.yellow>.portlet-title>.actions .btn-default>i {
        color: #e9dbbb
    }

    .portlet.box.yellow>.portlet-title>.actions .btn-default.active,
    .portlet.box.yellow>.portlet-title>.actions .btn-default:active,
    .portlet.box.yellow>.portlet-title>.actions .btn-default:focus,
    .portlet.box.yellow>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #ecdfc3;
        color: #f2ead6
    }

    .portlet.solid.yellow-gold>.portlet-body,
    .portlet.solid.yellow-gold>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.yellow-gold>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.yellow-gold>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.yellow-gold>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.yellow-gold>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.yellow-gold>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.yellow-gold>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.yellow-gold>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.yellow-gold {
        border: 1px solid #fb9724;
        border-top: 0
    }

    .portlet.box.yellow-gold>.portlet-title>.caption,
    .portlet.box.yellow-gold>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.yellow-gold>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #fcb460;
        color: #fdbf79
    }

    .portlet.box.yellow-casablanca>.portlet-title,
    .portlet.yellow-casablanca,
    .portlet>.portlet-body.yellow-casablanca {
        background-color: #f2784b
    }

    .portlet.box.yellow-gold>.portlet-title>.actions .btn-default>i {
        color: #fdc788
    }

    .portlet.box.yellow-gold>.portlet-title>.actions .btn-default.active,
    .portlet.box.yellow-gold>.portlet-title>.actions .btn-default:active,
    .portlet.box.yellow-gold>.portlet-title>.actions .btn-default:focus,
    .portlet.box.yellow-gold>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #fdcb92;
        color: #fed7ab
    }

    .portlet.solid.yellow-casablanca>.portlet-body,
    .portlet.solid.yellow-casablanca>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.yellow-casablanca>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.yellow-casablanca>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.yellow-casablanca>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.yellow-casablanca>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.yellow-casablanca>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.yellow-casablanca>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.yellow-casablanca>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.yellow-casablanca {
        border: 1px solid #f59c7b;
        border-top: 0
    }

    .portlet.box.yellow-casablanca>.portlet-title>.caption,
    .portlet.box.yellow-casablanca>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.yellow-casablanca>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #fac6b4;
        color: #fbd8cb
    }

    .portlet.box.yellow-crusta>.portlet-title,
    .portlet.yellow-crusta,
    .portlet>.portlet-body.yellow-crusta {
        background-color: #f3c200
    }

    .portlet.box.yellow-casablanca>.portlet-title>.actions .btn-default>i {
        color: #fce3da
    }

    .portlet.box.yellow-casablanca>.portlet-title>.actions .btn-default.active,
    .portlet.box.yellow-casablanca>.portlet-title>.actions .btn-default:active,
    .portlet.box.yellow-casablanca>.portlet-title>.actions .btn-default:focus,
    .portlet.box.yellow-casablanca>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #fdeae3;
        color: #fffcfb
    }

    .portlet.solid.yellow-crusta>.portlet-body,
    .portlet.solid.yellow-crusta>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.yellow-crusta>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.yellow-crusta>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.yellow-crusta>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.yellow-crusta>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.yellow-crusta>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.yellow-crusta>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.yellow-crusta>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.yellow-crusta {
        border: 1px solid #ffd327;
        border-top: 0
    }

    .portlet.box.yellow-crusta>.portlet-title>.caption,
    .portlet.box.yellow-crusta>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.yellow-crusta>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #ffe064;
        color: #ffe57e
    }

    .portlet.box.yellow-lemon>.portlet-title,
    .portlet.yellow-lemon,
    .portlet>.portlet-body.yellow-lemon {
        background-color: #F7CA18
    }

    .portlet.box.yellow-crusta>.portlet-title>.actions .btn-default>i {
        color: #ffe88d
    }

    .portlet.box.yellow-crusta>.portlet-title>.actions .btn-default.active,
    .portlet.box.yellow-crusta>.portlet-title>.actions .btn-default:active,
    .portlet.box.yellow-crusta>.portlet-title>.actions .btn-default:focus,
    .portlet.box.yellow-crusta>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #ffea97;
        color: #ffefb1
    }

    .portlet.solid.yellow-lemon>.portlet-body,
    .portlet.solid.yellow-lemon>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.yellow-lemon>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.yellow-lemon>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.yellow-lemon>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.yellow-lemon>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.yellow-lemon>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.yellow-lemon>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.yellow-lemon>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.yellow-lemon {
        border: 1px solid #f9d549;
        border-top: 0
    }

    .portlet.box.yellow-lemon>.portlet-title>.caption,
    .portlet.box.yellow-lemon>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.yellow-lemon>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #fbe384;
        color: #fce99d
    }

    .portlet.box.yellow-saffron>.portlet-title,
    .portlet.yellow-saffron,
    .portlet>.portlet-body.yellow-saffron {
        background-color: #F4D03F
    }

    .portlet.box.yellow-lemon>.portlet-title>.actions .btn-default>i {
        color: #fcecac
    }

    .portlet.box.yellow-lemon>.portlet-title>.actions .btn-default.active,
    .portlet.box.yellow-lemon>.portlet-title>.actions .btn-default:active,
    .portlet.box.yellow-lemon>.portlet-title>.actions .btn-default:focus,
    .portlet.box.yellow-lemon>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #fceeb6;
        color: #fdf4ce
    }

    .portlet.solid.yellow-saffron>.portlet-body,
    .portlet.solid.yellow-saffron>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.yellow-saffron>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.yellow-saffron>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.yellow-saffron>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.yellow-saffron>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.yellow-saffron>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.yellow-saffron>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.yellow-saffron>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.yellow-saffron {
        border: 1px solid #f7dc6f;
        border-top: 0
    }

    .portlet.box.yellow-saffron>.portlet-title>.caption,
    .portlet.box.yellow-saffron>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.yellow-saffron>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #faeaa9;
        color: #fbf0c1
    }

    .portlet.box.yellow-soft>.portlet-title,
    .portlet.yellow-soft,
    .portlet>.portlet-body.yellow-soft {
        background-color: #c8d046
    }

    .portlet.box.yellow-saffron>.portlet-title>.actions .btn-default>i {
        color: #fcf3d0
    }

    .portlet.box.yellow-saffron>.portlet-title>.actions .btn-default.active,
    .portlet.box.yellow-saffron>.portlet-title>.actions .btn-default:active,
    .portlet.box.yellow-saffron>.portlet-title>.actions .btn-default:focus,
    .portlet.box.yellow-saffron>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #fdf6d9;
        color: #fefcf1
    }

    .portlet.solid.yellow-soft>.portlet-body,
    .portlet.solid.yellow-soft>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.yellow-soft>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.yellow-soft>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.yellow-soft>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.yellow-soft>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.yellow-soft>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.yellow-soft>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.yellow-soft>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.yellow-soft {
        border: 1px solid #d4da6f;
        border-top: 0
    }

    .portlet.box.yellow-soft>.portlet-title>.caption,
    .portlet.box.yellow-soft>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.yellow-soft>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #e3e79f;
        color: #e9ecb4
    }

    .portlet.box.yellow-haze>.portlet-title,
    .portlet.yellow-haze,
    .portlet>.portlet-body.yellow-haze {
        background-color: #c5bf66
    }

    .portlet.box.yellow-soft>.portlet-title>.actions .btn-default>i {
        color: #ecefc0
    }

    .portlet.box.yellow-soft>.portlet-title>.actions .btn-default.active,
    .portlet.box.yellow-soft>.portlet-title>.actions .btn-default:active,
    .portlet.box.yellow-soft>.portlet-title>.actions .btn-default:focus,
    .portlet.box.yellow-soft>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #eff1c8;
        color: #f5f6dc
    }

    .portlet.solid.yellow-haze>.portlet-body,
    .portlet.solid.yellow-haze>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.yellow-haze>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.yellow-haze>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.yellow-haze>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.yellow-haze>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.yellow-haze>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.yellow-haze>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.yellow-haze>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.yellow-haze {
        border: 1px solid #d3ce8b;
        border-top: 0
    }

    .portlet.box.yellow-haze>.portlet-title>.caption,
    .portlet.box.yellow-haze>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.yellow-haze>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #e4e1b7;
        color: #ebe9ca
    }

    .portlet.box.yellow-mint>.portlet-title,
    .portlet.yellow-mint,
    .portlet>.portlet-body.yellow-mint {
        background-color: #c5b96b
    }

    .portlet.box.yellow-haze>.portlet-title>.actions .btn-default>i {
        color: #efedd5
    }

    .portlet.box.yellow-haze>.portlet-title>.actions .btn-default.active,
    .portlet.box.yellow-haze>.portlet-title>.actions .btn-default:active,
    .portlet.box.yellow-haze>.portlet-title>.actions .btn-default:focus,
    .portlet.box.yellow-haze>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #f2f1dc;
        color: #f9f8ef
    }

    .portlet.solid.yellow-mint>.portlet-body,
    .portlet.solid.yellow-mint>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.yellow-mint>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.yellow-mint>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.yellow-mint>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.yellow-mint>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.yellow-mint>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.yellow-mint>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.yellow-mint>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.yellow-mint {
        border: 1px solid #d3ca90;
        border-top: 0
    }

    .portlet.box.yellow-mint>.portlet-title>.caption,
    .portlet.box.yellow-mint>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.yellow-mint>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #e5dfbc;
        color: #ece8ce
    }

    .portlet.box.purple>.portlet-title,
    .portlet.purple,
    .portlet>.portlet-body.purple {
        background-color: #8E44AD
    }

    .portlet.box.yellow-mint>.portlet-title>.actions .btn-default>i {
        color: #f0edd9
    }

    .portlet.box.yellow-mint>.portlet-title>.actions .btn-default.active,
    .portlet.box.yellow-mint>.portlet-title>.actions .btn-default:active,
    .portlet.box.yellow-mint>.portlet-title>.actions .btn-default:focus,
    .portlet.box.yellow-mint>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #f3f0e0;
        color: #faf9f3
    }

    .portlet.solid.purple>.portlet-body,
    .portlet.solid.purple>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.purple>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.purple>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.purple>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.purple>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.purple>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.purple>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.purple>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.purple {
        border: 1px solid #a563c1;
        border-top: 0
    }

    .portlet.box.purple>.portlet-title>.caption,
    .portlet.box.purple>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.purple>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #bf8ed3;
        color: #c9a1da
    }

    .portlet.box.purple-plum>.portlet-title,
    .portlet.purple-plum,
    .portlet>.portlet-body.purple-plum {
        background-color: #8775a7
    }

    .portlet.box.purple>.portlet-title>.actions .btn-default>i {
        color: #cfacde
    }

    .portlet.box.purple>.portlet-title>.actions .btn-default.active,
    .portlet.box.purple>.portlet-title>.actions .btn-default:active,
    .portlet.box.purple>.portlet-title>.actions .btn-default:focus,
    .portlet.box.purple>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #d4b3e1;
        color: #dec5e8
    }

    .portlet.solid.purple-plum>.portlet-body,
    .portlet.solid.purple-plum>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.purple-plum>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.purple-plum>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.purple-plum>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.purple-plum>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.purple-plum>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.purple-plum>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.purple-plum>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.purple-plum {
        border: 1px solid #a294bb;
        border-top: 0
    }

    .portlet.box.purple-plum>.portlet-title>.caption,
    .portlet.box.purple-plum>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.purple-plum>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #c3bad3;
        color: #d0c9dd
    }

    .portlet.box.purple-medium>.portlet-title,
    .portlet.purple-medium,
    .portlet>.portlet-body.purple-medium {
        background-color: #BF55EC
    }

    .portlet.box.purple-plum>.portlet-title>.actions .btn-default>i {
        color: #d8d2e3
    }

    .portlet.box.purple-plum>.portlet-title>.actions .btn-default.active,
    .portlet.box.purple-plum>.portlet-title>.actions .btn-default:active,
    .portlet.box.purple-plum>.portlet-title>.actions .btn-default:focus,
    .portlet.box.purple-plum>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #ded9e7;
        color: #ebe8f0
    }

    .portlet.solid.purple-medium>.portlet-body,
    .portlet.solid.purple-medium>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.purple-medium>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.purple-medium>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.purple-medium>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.purple-medium>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.purple-medium>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.purple-medium>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.purple-medium>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.purple-medium {
        border: 1px solid #d083f1;
        border-top: 0
    }

    .portlet.box.purple-medium>.portlet-title>.caption,
    .portlet.box.purple-medium>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.purple-medium>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #e5baf7;
        color: #eed1fa
    }

    .portlet.box.purple-studio>.portlet-title,
    .portlet.purple-studio,
    .portlet>.portlet-body.purple-studio {
        background-color: #8E44AD
    }

    .portlet.box.purple-medium>.portlet-title>.actions .btn-default>i {
        color: #f3dffb
    }

    .portlet.box.purple-medium>.portlet-title>.actions .btn-default.active,
    .portlet.box.purple-medium>.portlet-title>.actions .btn-default:active,
    .portlet.box.purple-medium>.portlet-title>.actions .btn-default:focus,
    .portlet.box.purple-medium>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #f6e8fc;
        color: #fff
    }

    .portlet.solid.purple-studio>.portlet-body,
    .portlet.solid.purple-studio>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.purple-studio>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.purple-studio>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.purple-studio>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.purple-studio>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.purple-studio>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.purple-studio>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.purple-studio>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.purple-studio {
        border: 1px solid #a563c1;
        border-top: 0
    }

    .portlet.box.purple-studio>.portlet-title>.caption,
    .portlet.box.purple-studio>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.purple-studio>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #bf8ed3;
        color: #c9a1da
    }

    .portlet.box.purple-wisteria>.portlet-title,
    .portlet.purple-wisteria,
    .portlet>.portlet-body.purple-wisteria {
        background-color: #9B59B6
    }

    .portlet.box.purple-studio>.portlet-title>.actions .btn-default>i {
        color: #cfacde
    }

    .portlet.box.purple-studio>.portlet-title>.actions .btn-default.active,
    .portlet.box.purple-studio>.portlet-title>.actions .btn-default:active,
    .portlet.box.purple-studio>.portlet-title>.actions .btn-default:focus,
    .portlet.box.purple-studio>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #d4b3e1;
        color: #dec5e8
    }

    .portlet.solid.purple-wisteria>.portlet-body,
    .portlet.solid.purple-wisteria>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.purple-wisteria>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.purple-wisteria>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.purple-wisteria>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.purple-wisteria>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.purple-wisteria>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.purple-wisteria>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.purple-wisteria>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.purple-wisteria {
        border: 1px solid #b07cc6;
        border-top: 0
    }

    .portlet.box.purple-wisteria>.portlet-title>.caption,
    .portlet.box.purple-wisteria>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.purple-wisteria>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #caa7d8;
        color: #d5b9e0
    }

    .portlet.box.purple-seance>.portlet-title,
    .portlet.purple-seance,
    .portlet>.portlet-body.purple-seance {
        background-color: #9A12B3
    }

    .portlet.box.purple-wisteria>.portlet-title>.actions .btn-default>i {
        color: #dbc3e5
    }

    .portlet.box.purple-wisteria>.portlet-title>.actions .btn-default.active,
    .portlet.box.purple-wisteria>.portlet-title>.actions .btn-default:active,
    .portlet.box.purple-wisteria>.portlet-title>.actions .btn-default:focus,
    .portlet.box.purple-wisteria>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #dfcae8;
        color: #eadcf0
    }

    .portlet.solid.purple-seance>.portlet-body,
    .portlet.solid.purple-seance>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.purple-seance>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.purple-seance>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.purple-seance>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.purple-seance>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.purple-seance>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.purple-seance>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.purple-seance>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.purple-seance {
        border: 1px solid #c217e1;
        border-top: 0
    }

    .portlet.box.purple-seance>.portlet-title>.caption,
    .portlet.box.purple-seance>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.purple-seance>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #d349ed;
        color: #d960ef
    }

    .portlet.box.purple-intense>.portlet-title,
    .portlet.purple-intense,
    .portlet>.portlet-body.purple-intense {
        background-color: #8775a7
    }

    .portlet.box.purple-seance>.portlet-title>.actions .btn-default>i {
        color: #dc6ef0
    }

    .portlet.box.purple-seance>.portlet-title>.actions .btn-default.active,
    .portlet.box.purple-seance>.portlet-title>.actions .btn-default:active,
    .portlet.box.purple-seance>.portlet-title>.actions .btn-default:focus,
    .portlet.box.purple-seance>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #de77f1;
        color: #e48ef4
    }

    .portlet.solid.purple-intense>.portlet-body,
    .portlet.solid.purple-intense>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.purple-intense>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.purple-intense>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.purple-intense>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.purple-intense>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.purple-intense>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.purple-intense>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.purple-intense>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.purple-intense {
        border: 1px solid #a294bb;
        border-top: 0
    }

    .portlet.box.purple-intense>.portlet-title>.caption,
    .portlet.box.purple-intense>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.purple-intense>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #c3bad3;
        color: #d0c9dd
    }

    .portlet.box.purple-sharp>.portlet-title,
    .portlet.purple-sharp,
    .portlet>.portlet-body.purple-sharp {
        background-color: #796799
    }

    .portlet.box.purple-intense>.portlet-title>.actions .btn-default>i {
        color: #d8d2e3
    }

    .portlet.box.purple-intense>.portlet-title>.actions .btn-default.active,
    .portlet.box.purple-intense>.portlet-title>.actions .btn-default:active,
    .portlet.box.purple-intense>.portlet-title>.actions .btn-default:focus,
    .portlet.box.purple-intense>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #ded9e7;
        color: #ebe8f0
    }

    .portlet.solid.purple-sharp>.portlet-body,
    .portlet.solid.purple-sharp>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.purple-sharp>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.purple-sharp>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.purple-sharp>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.purple-sharp>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.purple-sharp>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.purple-sharp>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.purple-sharp>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.purple-sharp {
        border: 1px solid #9486ad;
        border-top: 0
    }

    .portlet.box.purple-sharp>.portlet-title>.caption,
    .portlet.box.purple-sharp>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.purple-sharp>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #b4aac6;
        color: #c2b9d0
    }

    .portlet.box.purple-soft>.portlet-title,
    .portlet.purple-soft,
    .portlet>.portlet-body.purple-soft {
        background-color: #8877a9
    }

    .portlet.box.purple-sharp>.portlet-title>.actions .btn-default>i {
        color: #cac3d6
    }

    .portlet.box.purple-sharp>.portlet-title>.actions .btn-default.active,
    .portlet.box.purple-sharp>.portlet-title>.actions .btn-default:active,
    .portlet.box.purple-sharp>.portlet-title>.actions .btn-default:focus,
    .portlet.box.purple-sharp>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #cfc9db;
        color: #ddd8e5
    }

    .portlet.solid.purple-soft>.portlet-body,
    .portlet.solid.purple-soft>.portlet-title {
        border: 0;
        color: #fff
    }

    .portlet.solid.purple-soft>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.solid.purple-soft>.portlet-title>.tools>a.remove {
        background-image: url(../img/portlet-remove-icon-white.png)
    }

    .portlet.solid.purple-soft>.portlet-title>.tools>a.config {
        background-image: url(../img/portlet-config-icon-white.png)
    }

    .portlet.solid.purple-soft>.portlet-title>.tools>a.reload {
        background-image: url(../img/portlet-reload-icon-white.png)
    }

    .portlet.solid.purple-soft>.portlet-title>.tools>a.expand {
        background-image: url(../img/portlet-expand-icon-white.png)
    }

    .portlet.solid.purple-soft>.portlet-title>.tools>a.collapse {
        background-image: url(../img/portlet-collapse-icon-white.png)
    }

    .portlet.solid.purple-soft>.portlet-title>.tools>a.fullscreen {
        color: #fdfdfd
    }

    .portlet.box.purple-soft {
        border: 1px solid #a396bd;
        border-top: 0
    }

    .portlet.box.purple-soft>.portlet-title>.caption,
    .portlet.box.purple-soft>.portlet-title>.caption>i {
        color: #fff
    }

    .portlet.box.purple-soft>.portlet-title>.actions .btn-default {
        background: 0 0 !important;
        border: 1px solid #c4bcd4;
        color: #d2cbde
    }

    .portlet.box.purple-soft>.portlet-title>.actions .btn-default>i {
        color: #dad5e4
    }

    .portlet.box.purple-soft>.portlet-title>.actions .btn-default.active,
    .portlet.box.purple-soft>.portlet-title>.actions .btn-default:active,
    .portlet.box.purple-soft>.portlet-title>.actions .btn-default:focus,
    .portlet.box.purple-soft>.portlet-title>.actions .btn-default:hover {
        border: 1px solid #dfdbe8;
        color: #edebf2
    }
</style>