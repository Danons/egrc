<div class="row">
	<div class="col-sm-12">
		<?php

		$from = UI::createTextArea('review', null, '', '', true, 'form-control', " placeholder='ketik disini untuk menambah pesan'");
		echo UI::createFormGroup($from, $rules["deskripsi"], "deskripsi", "<b>Catatan</b>", true);

		$from = UI::showButtonMode("save", null, true);
		echo UI::createFormGroup($from, null, null, null, true);

		foreach ($list['rows'] as $r) {

			// if ($r['created_by'] == $_SESSION[SESSION_APP]['user_id'])
			// 	$this->access_role['delete'] = 1;

			// $btn = UI::getButton('delete', $r['id_review'], null, 'btn-xs');
			$btn = "";
			echo "
				<small>
					<b>" . ucwords(strtolower($r['nama_user'])) . " (" . ucwords(strtolower($r['nama_group'])) . ")</b>
					<br/>
					<i>" . $r['review'] . "</i>
					<span class='float-right'>$btn</span>
				</small>
				<br/>
				<small style='font-size:10px'>" . Eng2Ind($r['created_date']) . "
				</small>
				<hr/>";
		}
		?>
	</div>
</div>