
    <?= UI::createSelect('is_user', $arrIs_user, $this->post['is_user'], $edited, 'form-control select'); ?>
    <?= UI::createTextArea('msg', $row['msg'], '10', '', $edited, 'form-control contents'); ?>
    <?= UI::showButtonMode("save", null, $edited); ?>
   

