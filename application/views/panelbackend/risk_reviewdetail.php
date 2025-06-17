<div class="body table-responsive">

  <?php
    var_dump($rssql_userreview);
  ?>

  <?php
  $from =  UI::createTextArea('keterangan['.$nameid.']',null,'','',true,$class='form-control'," placeholder='ketik disini untuk menambah komentar'");
  echo UI::createFormGroup($from, $rules["review"], "Komentar", "Komentar");
  ?>

  <div class="modal-footer">
      <button type="button" class="btn btn-secondary  btn-sm" data-bs-dismiss="modal">CLOSE</button>
      <button type="button" class="btn btn-secondary  btn-sm">SEND</button>
  </div>
</div>
