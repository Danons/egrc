<div class="row1">
  <div id="<?= $url ?>" class="url"></div>
  <?php if (Access("view_all","main")) { ?>
  <div style="margin-left: auto;margin-right: auto;margin-top: 20px;display: revert;width: 100px;text-align: center;">
    <button id="download">download</button>
  </div>
  <?php } ?>
  <br>
  <canvas id="the-canvas1" class="the-canvas"></canvas>
</div>
<style>
  .the-canvas {
    border: 1px solid black;
    direction: ltr;
    display: block;
    margin-left: auto;
    margin-right: auto;
  }
</style>
<script>
  $(document).ready(function() {
    $('.url').hide();
  })
</script>