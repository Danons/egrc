<?php
class UI
{
	private $auth = array();

	public static function FormGroup($array = array())
	{
		return self::createFormGroup($array['form'], $array['rule'], $array['name'], $array['label'], $array['onlyone'], $array['sm_label'], $array['edited']);
	}

	public static function createFormGroup($form = null, $rule = null, $name = null, $label = null, $onlyone = false, $sm_label = 4, $edited = true)
	{
		// dpr($edited, 1);
		if (!$form)
			return;

		if ($onlyone) {

			if ($edited && $rule)
				$form_error = form_error($name);

			$ret = '
<div class="mb-3 row ' . (($form_error) ? 'has-error' : '') . '">';
			if ($label) {
				$ret .= '
	<label for="' . $name . '" class="col-sm-12 control-label">' . $label;
				if (strstr($rule['rules'], 'required') !== false && $edited) {
					$ret .= '&nbsp;<span style="color:#dd4b39">*</span>';
				}

				$ret .= '
	</label>';
			}
			$ret .= '
	<div class="col-sm-12">
		' . $form;
			if ($edited) {
				$ret .= '
		<span style="color:#dd4b39; font-size:11px; ' . (($form_error) ? '' : 'display: none') . '" id="info_' . $name . '">
		' . $form_error . '
		</span>';
			}
			$ret .= '</div>
			</div>';
			return $ret;
		}

		$sm_form = 12 - $sm_label;
		if (!$rule['rules']) {

			$ret = '
<div class="mb-3 row">
	<label for="' . $name . '" class="col-sm-' . $sm_label . ' control-label">
		' . $label . '
	</label>
	<div class="col-sm-' . $sm_form . '">' . $form . '
	</div>
</div>';
			return $ret;
		}

		if ($edited)
			$form_error = form_error($name);

		$ret = '
<div class="mb-3 row ' . (($form_error) ? 'has-error' : '') . '">
	<label for="' . $name . '" class="col-sm-' . $sm_label . ' control-label">
		' . $label;
		if (strstr($rule['rules'], 'required') !== false && $edited) {
			$ret .= '&nbsp;<span style="color:#dd4b39">*</span>';
		}

		$ret .= '
	</label>
	<div class="col-sm-' . $sm_form . '">' . $form;
		$ret .= '
			<span style="color:#dd4b39; font-size:11px; ' . (($form_error) ? '' : 'display: none') . '" id="info_' . $name . '">
			' . $form_error . '
			</span>';
		$ret .= '
	</div>
</div>';
		return $ret;
	}

	public static function createFormGroupPlain($form = null, $rule = null, $name = null, $label = null, $onlyone = false, $edited = true, $label_class = null)
	{

		if (!$label_class) $label_class = "col-sm-2";

		if (!$form)
			return;

		if ($onlyone) {

			$ret = '
<div class="mb-3 row">
	<div class="col-sm-input">
	' . $form . '
	</div>
</div>';
			return $ret;
		}

		if (!$rule['rules']) {

			$ret = '
<div class="mb-3 row">
	<label for="' . $name . '" class="' . $label_class . ' control-label" style="text-align: left;">
		' . $label . '
	</label>
	<div class="col-sm-input">' . $form . '
	</div>
</div>';
			return $ret;
		}

		if ($edited)
			$form_error = form_error($name);

		$ret = '
<div class="mb-3 row ' . (($form_error) ? 'has-error' : '') . '">
	<label for="' . $name . '" class="col-sm control-label">
		' . $label;
		if (strstr($rule['rules'], 'required') !== false && $edited) {
			$ret .= '&nbsp;<span style="color:#dd4b39">*</span>';
		}

		$ret .= '
	</label>
	<div class="col-sm-input">' . $form;
		$ret .= '
			<span style="color:#dd4b39; font-size:11px; ' . (($form_error) ? '' : 'display: none') . '" id="info_' . $name . '">
			' . $form_error . '
			</span>';
		$ret .= '
	</div>
</div>';
		return $ret;
	}

	public static function createTextArea($nameid, $value = '', $rows = '', $cols = '', $edit = true, $class = 'form-control', $add = '')
	{
		//if (empty($class))
		//    $class = 'control_style';

		if (!empty($edit)) {
			$ta = '<div class="form-line"><textarea wrap="soft" name="' . $nameid . '" id="' . $nameid . '"';
			if ($class != '') $ta .= ' class="' . $class . '"';
			if ($rows != '') $ta .= ' rows="' . $rows . '"';
			if ($cols != '') $ta .= ' cols="' . $cols . '"';
			if ($add != '') $ta .= ' ' . $add;
			$ta .= '>';
			if ($value != '') $ta .= $value;
			$ta .= '</textarea></div>';
		} else if ($value == '')
			$ta = '<i style="color:#aaa" class="read_detail">-</i>';
		else {
			if (strstr($class, "contents") !== false) {
				$ta = "<span class='read_detail'>" . ($value) . "</span>";
			} else {
				$ta = "<span class='read_detail'>" . nl2br($value) . "</span>";
			}
		}

		return $ta;
	}

	public static function createTextEditor($nameid, $value = '', $rows = '', $cols = '', $edit = true, $class = 'form-control', $add = '')
	{
		//if (empty($class))
		//    $class = 'control_style';

		if (!empty($edit)) {
			$ta = '<div class="form-line"><textarea wrap="soft" name="' . $nameid . '" id="' . $nameid . '"';
			if ($class != '') $ta .= ' class="' . $class . '"';
			if ($rows != '') $ta .= ' rows="' . $rows . '"';
			if ($cols != '') $ta .= ' cols="' . $cols . '"';
			if ($add != '') $ta .= ' ' . $add;
			$ta .= '>';
			if ($value != '') $ta .= $value;
			$ta .= '</textarea></div>';
		} else if ($value == '')
			$ta = '<i style="color:#aaa" class="read_detail">-</i>';
		else
			$ta = "<span class='read_detail'>" . ($value) . "</span>";

		return $ta;
	}

	// membuat textbox
	public static function TextBox($arr = array())
	{

		return self::createTextBox($arr['name'], $arr['value'], $arr['maxlength'], $arr['size'], $arr['edited'], $arr['class'], $arr['add']);
	}

	// membuat textbox
	public static function createTextBox($nameid, $value = '', $maxlength = '', $size = '', $edit = true, $class = 'form-control', $add = '')
	{
		//if (empty($class))
		//    $class = 'control_style';

		if (!empty($edit)) {
			$tb = '<div class="form-line"><input type="text" name="' . $nameid . '" id="' . $nameid . '"';
			if ($value != '') $tb .= ' value="' . $value . '"';
			if ($class != '') $tb .= ' class="' . $class . '"';
			if ($maxlength != '') $tb .= ' maxlength="' . $maxlength . '"';
			if ($size != '') $tb .= ' size="' . $size . '"';
			if ($add != '') $tb .= ' ' . $add;
			$tb .= '></div>';
		} else if ($value == '')
			$tb = '<i style="color:#aaa" class="read_detail">-</i>';
		else {
			$class = str_replace('form-control', '', $class);
			if (strstr($class, "datepicker") !== false)
				$tb = "<span class='$class read_detail'>" . Eng2Ind($value) . "</span>";
			else
				$tb = "<span class='$class read_detail'>" . $value . "</span>";
		}

		return $tb;
	}

	// membuat texthidden
	public static function createTextHidden($nameid, $value = '', $edit = true, $add = '')
	{

		if (!empty($edit)) {
			$tb = '<input type="hidden" name="' . $nameid . '" id="' . $nameid . '"';
			if ($value != '') $tb .= ' value="' . $value . '"';
			if ($add != '') $tb .= ' ' . $add;
			$tb .= '>';
		}

		return $tb;
	}

	// membuat textbox
	public static function createTextDate($nameid, $value = '', $maxlength = '', $size = '', $edit = true, $class = 'form-control', $add = '')
	{
		//if (empty($class))
		//    $class = 'control_style';

		if (!empty($edit)) {
			$tb = '<input type="text" name="' . $nameid . '" id="' . $nameid . '"';
			if ($value != '') $tb .= ' value="' . $value . '"';
			$tb .= ' class="datepicker ' . $class . '"';
			if ($maxlength != '') $tb .= ' maxlength="' . $maxlength . '"';
			if ($size != '') $tb .= ' size="' . $size . '"';
			if ($add != '') $tb .= ' ' . $add;
			$tb .= '>';
		} else if ($value == '')
			$tb = '<i style="color:#aaa" class="read_detail">-</i>';
		else
			$tb = "<span class='read_detail'>" . $value . "</span>";

		return $tb;
	}

	// membuat textbox
	public static function createAutoComplate($nameid, $value = array(), $url, $maxlength = '', $size = '', $edit = true, $class = 'form-control', $add = '')
	{
		//if (empty($class))
		//    $class = 'control_style';

		if (!empty($edit)) {
			$tb = '<input autocomplete="off" type="text" name="name' . $nameid . '" id="name' . $nameid . '"';
			if ($value['label'] != '') $tb .= ' value="' . $value['label'] . '"';
			if ($class != '') $tb .= ' class="' . $class . '"';
			if ($maxlength != '') $tb .= ' maxlength="' . $maxlength . '"';
			if ($size != '') $tb .= ' size="' . $size . '"';
			if ($add != '') $tb .= ' ' . $add;
			$tb .= '>';

			$tb .= '<input type="hidden" name="' . $nameid . '" id="' . $nameid . '"';
			if ($value['id']) $tb .= ' value="' . $value['id'] . '"';
			$tb .= '/>';

			$tb .= '<script>
			$(function(){
				$("#' . $nameid . '").autocomplete("' . base_url($url) . '");
			});
			</script>';
		} else if ($value['label'] == '')
			$tb = '<i style="color:#aaa" class="read_detail">-</i>';
		else
			$tb = "<span class='read_detail'>" . $value['label'] . "</span>";


		return $tb;
	}

	// membuat textbox 'file',$row['nama_file'], site_url("panelbackend/preview_file/$row[id_buku]"), site_url("panelbackend/delete_file/$row[id_buku]"), $edited, false, 'form-control'

	public static function InputFile($array = array())
	{
		$default = array(
			"edit" => false,
			"ispreview" => false,
			"class" => "form-control",
		);
		foreach ($default as $idkey => $value) {
			if ($array[$idkey] === null)
				$array[$idkey] = $value;
		}
		return self::createInputFile($array['nameid'], $array['nama_file'], $array['url_preview'], $array['url_delete'], $array['edit'], $array['ispreview'], $array['class'], $array['add'], $array['extarr']);
	}
	public static function createInputFile($nameid, $nama_file = '', $url_preview = '', $url_delete = '', $edit = true, $ispreview = false, $class = 'form-control', $add = 'style="width:auto"', $extarr = array())
	{
		//if (empty($class))
		//    $class = 'control_style';

		if (!empty($edit) && (!$nama_file or !$url_delete)) {
			$accept = "";
			$tb = '';
			if (($extarr)) {
				$accept = 'accept="' . implode(', ', $extarr) . '"';
				$tb .= '<span class="badge bg-info">.' . implode(', .', $extarr) . '</span>';
			}
			$tb .= '<input type="file" name="' . $nameid . '" id="' . $nameid . '"';
			if ($class != '') $tb .= ' class="' . $class . '"';
			if ($add != '') $tb .= ' ' . $add;
			$tb .= $accept;
			$tb .= '>';
		} else if ($nama_file == '')
			$tb = '<i style="color:#aaa" class="read_detail">-</i>';



		if ($ispreview && $url_preview && $nama_file) {
			$tb .= "<img src='$url_preview'/>";
		}
		if ($nama_file) {
			$tb .= "<div style='clear:both'></div>";
			if ($url_preview) {
				$tb .= "<a target='_blank' href='$url_preview'>$nama_file</a> ";
			} else {
				$tb .= "$nama_file&nbsp; ";
			}
			if (!empty($edit) && $nama_file && $url_delete) {
				$tb .= "<a href='$url_delete'><i class='bi bi-trash' style='color:red'></i></a> ";
			}
			$tb .= "<div style='clear:both'></div>";
		}
		return $tb;
	}

	// membuat textbox
	public static function createTextNumber($nameid, $value = '', $maxlength = '', $size = '', $edit = true, $class = 'form-control', $add = '')
	{
		//if (empty($class))
		//    $class = 'control_style';

		if (!empty($edit)) {
			$tb = '<div class="form-line"><input type="number" name="' . $nameid . '" id="' . $nameid . '"';
			if ($value != '') $tb .= ' value="' . $value . '"';
			if ($class != '') $tb .= ' class="' . $class . '"';
			if ($maxlength != '') $tb .= ' maxlength="' . $maxlength . '"';
			if ($size != '') $tb .= ' size="' . $size . '"';
			if ($add != '') $tb .= ' ' . $add;
			$tb .= '></div>';
		} else if ($value == '')
			$tb = '<i style="color:#aaa" class="read_detail">-</i>';
		else
			$tb = "<span class='read_detail'>" . ($value) . "</span>";

		return $tb;
	}

	// membuat textbox
	public static function createTextPassword($nameid, $value = '', $maxlength = '', $size = '', $edit = true, $class = 'form-control', $add = '')
	{
		//if (empty($class))
		//    $class = 'control_style';

		if (!empty($edit)) {
			$tb = '<div class="form-line"><input type="password" name="' . $nameid . '" id="' . $nameid . '"';
			if ($value != '') $tb .= ' value="' . $value . '"';
			if ($class != '') $tb .= ' class="' . $class . '"';
			if ($maxlength != '') $tb .= ' maxlength="' . $maxlength . '"';
			if ($size != '') $tb .= ' size="' . $size . '"';
			if ($add != '') $tb .= ' ' . $add;
			$tb .= '></div>';
		} else if ($value == '')
			$tb = '<i style="color:#aaa" class="read_detail">-</i>';
		else
			$tb = "<span class='read_detail'>" . $value . "</span>";

		return $tb;
	}

	// membuat combo box
	public static function createSelect($nameid, $arrval = '', $value = '', $edit = true, $class = 'form-control', $add = '', $emptyrow = false)
	{

		if (!$edit)
			$arrval[''] = '<i>-</i>';

		$placeholder = "Pilih...";
		if ($arrval['']) {
			$placeholder = $arrval[''];
		}

		if (!empty($edit)) {
			if ($nameid == 'list_limit')
				$slc = '<div class="form-line"><select style="width:auto" data-placeholder="' . $placeholder . '" tabindex="2" name="' . $nameid . '" id="' . $nameid . '"';
			else
				$slc = '<div class="form-line"><select  data-placeholder="' . $placeholder . '" tabindex="2" name="' . $nameid . '" id="' . $nameid . '"';
			$slc .= ' class="' . (($class != '') ? $class : '') . '"';
			if ($add != '') $slc .= ' ' . $add;
			if (strstr($add, "stye") === false) {
				$add .= 'style="width:100%"';
			}
			$slc .= ">\n";
			if ($emptyrow)
				$slc .= '<option></option>' . "\n";
			if (is_array($arrval)) {
				foreach ($arrval as $idkey => $val) {
					$slc .= '<option value="' . $idkey . '"' . (!strcasecmp($value, $idkey) ? ' selected' : '') . '>';
					$slc .= $val . '</option>' . "\n";
				}
			}
			$slc .= '</select></div>';
		} else {
			if (is_array($arrval)) {
				foreach ($arrval as $idkey => $val) {
					if (!strcasecmp($value, $idkey)) {
						$slc = "<span class='read_detail'>" . $val . "</span>";
						break;
					}
				}
			}
			if (!isset($slc))
				$slc = '&nbsp;';
		}

		return $slc;
	}

	// membuat combo box
	public static function createSelectMultiple($nameid, $arrval = '', $arrvalue = array(), $edit = true, $class = 'form-control', $add = '', $emptyrow = false)
	{
		if (!is_array($arrvalue)) $arrvalue = array($arrvalue);
		if (!empty($edit)) {
			$slc = '<div class="form-line">
				<select tabindex="4" multiple name="' . $nameid . '" id="' . $nameid . '"';
			$slc .= ' class="chosen-select ' . (($class != '') ? $class : '') . '"';
			if ($add != '') $slc .= ' ' . $add;
			$slc .= ">\n";
			if ($emptyrow)
				$slc .= '<option></option>' . "\n";
			if (is_array($arrval)) {
				foreach ($arrval as $idkey => $val) {
					$slc .= '<option value="' . $idkey . '"' . (in_array($idkey, $arrvalue) ? ' selected' : '') . '>';
					$slc .= $val . '</option>' . "\n";
				}
			}
			$slc .= '</select>
				</div>';
		} else {
			$value_d = array();
			if (is_array($arrval)) {
				foreach ($arrval as $idkey => $val) {
					if (in_array($idkey, $arrvalue)) {
						$value_d[] = $val;
					}
				}
			}
			$slc .= "<span class='read_detail'>" . implode(', ', $value_d) . "</span>";
			if (!isset($slc))
				$slc = '&nbsp;';
		}

		return $slc;
	}
	// membuat combo box
	public static function createSelectMultipleAutocomplate($nameid, $arrval = '', $arrvalue = array(), $edit = true, $class = 'form-control', $add = '', $emptyrow = false)
	{
		if (!is_array($arrvalue)) $arrvalue = array($arrvalue);
		if (!empty($edit)) {
			$slc = '<div class="form-line"><select  data-ajax--data-type="json" tabindex="4" multiple name="' . $nameid . '" id="' . $nameid . '"';
			$slc .= ' class="chosen-select ' . (($class != '') ? $class : '') . '"';
			if ($add != '') $slc .= ' ' . $add;
			$slc .= ">\n";
			if ($emptyrow)
				$slc .= '<option></option>' . "\n";
			if (is_array($arrval)) {
				foreach ($arrval as $idkey => $val) {
					$slc .= '<option value="' . $idkey . '"' . (in_array($idkey, $arrvalue) ? ' selected' : '') . '>';
					$slc .= $val . '</option>' . "\n";
				}
			}
			$slc .= '</select></div>';
		} else {
			$value_d = array();
			if (is_array($arrval)) {
				foreach ($arrval as $idkey => $val) {
					if (in_array($idkey, $arrvalue)) {
						$value_d[] = $val;
					}
				}
			}
			$slc .= "<span class='read_detail'>" . implode(', ', $value_d) . "</span>";
			if (!isset($slc))
				$slc = '&nbsp;';
		}

		return $slc;
	}

	// membuat combo box
	public static function createSelectKategori($nameid, $arrval = '', $value = '', $edit = true, $class = 'form-control', $add = '', $emptyrow = false)
	{
		if (!empty($edit)) {
			$slc = '<select name="' . $nameid . '" id="' . $nameid . '"';
			if ($class != '') $slc .= ' class="' . $class . '"';
			if ($add != '') $slc .= ' ' . $add;
			$slc .= ">\n";
			if ($emptyrow)
				$slc .= '<option></option>' . "\n";
			if (is_array($arrval)) {
				foreach ($arrval as $idkey => $val) {
					$slc .= '<option value="' . $idkey . '"' . (!strcasecmp($value, $idkey) ? ' selected' : '') . '>';
					$slc .= $val . '</option>' . "\n";
				}
			}
			$slc .= '</select>';
		} else {
			if (is_array($arrval)) {
				foreach ($arrval as $idkey => $val) {
					if (!strcasecmp($value, $idkey)) {
						$slc = $val;
						break;
					}
				}
			}
			if (!isset($slc))
				$slc = '&nbsp;';
		}

		return $slc;
	}

	// membuat textbox
	public static function createCheckBox($nameid, $valuecontrol = '', $value = '', $label = 'label', $edit = true, $class = '', $add = '')
	{
		//if (empty($class))
		//    $class = 'control_style';


		$tb = '<input type="checkbox" name="' . $nameid . '" id="' . $nameid . '"';
		if ($valuecontrol != '') {
			$tb .= ' value="' . $valuecontrol . '"';
			if ($value == $valuecontrol)
				$tb .= ' checked ';
		}
		if ($class != '') $tb .= ' class="' . $class . '"';
		if ($add != '') $tb .= ' ' . $add;
		if (!$edit)
			$tb .= ' disabled ';
		$tb .= '>';

		if ($label) {
			$tb .= "<label for='$nameid' style='margin: 0px; margin-top:-3px;
		    padding: 0px 10px;'><b>$label</b></label>";
		} else {
			$tb .= "<label for='$nameid' style='margin: 0px !important; margin-top:-3px;
		    padding: 0px !important;'></label>";
		}

		return $tb;
	}

	// membuat radio button
	public static function createRadio($nameid, $arrval = '', $value = '', $edit = true, $br = false, $class = 'form-control', $add = '')
	{
		//if (empty($class))
		//    $class = 'control_style';

		$radio = '';

		if (!empty($edit)) {
			if (is_array($arrval)) {
				foreach ($arrval as $idkey => $val) {
					$radio .= '<input type="radio" name="' . $nameid . '" id="' . $nameid . '_' . $idkey . '" value="' . $idkey . '"' . (!strcasecmp($value, $idkey) ? ' checked' : '') . ' ' . $add . '>';
					$radio .= '&nbsp;<label for="' . $nameid . '_' . $idkey . '"> ' . $val . '</label>' . ($br ? '<br>' : '&nbsp;&nbsp;') . "\n";
				}
			}
		} else {
			if (is_array($arrval)) {
				foreach ($arrval as $idkey => $val) {
					if (!strcasecmp($value, $idkey)) {
						$radio = "<span class='read_detail'>" . $val . "</span>";
						break;
					}
				}
			}
		}

		return $radio;
	}

	public static function showPaging($paging, $page, $limit_arr, $limit, $list)
	{
		if (!$list['total'])
			return;

		$batas_atas = $page + 1;
		$batas_bawah = $batas_atas + ($limit - 1);
		if ($batas_bawah > $list['total']) {
			$batas_bawah = $list['total'];
		}
?>
		<?php /*<div class="row">
			<div class="col-sm-5" style="margin-bottom: 0px">
				<div class="dataTables_info dataTables_length">
					Perhalaman
					<?php
					foreach ($limit_arr as $k => $v) {
						$limit_arr1[$v] = $v;
					}
					echo self::createSelect('list_limit', $limit_arr1, $limit, true, 'form-control input-sm', 'onchange="goLimit()"');
					?>
					Menampilkan <?= $batas_atas ?> sampai <?= $batas_bawah ?> dari total <?= $list['total'] ?> data

				</div>
			</div>
			<div class="col-sm-7" style="margin-bottom: 0px">
				<div class="dataTables_paginate paging_simple_numbers">
					<ul class="pagination">
						<?= $paging ?>
					</ul>
				</div>
			</div>
		</div>*/ ?>
		<small>
			<div class="d-flex">
				<div class="d-flex me-auto">
					Perhalaman &nbsp;
					<?php
					foreach ($limit_arr as $k => $v) {
						if ($k != -1)
							$k = $v;

						$limit_arr1[$k] = $v;
					}
					echo self::createSelect('list_limit', $limit_arr1, $limit, true, '', 'onchange="goLimit()"');
					?> &nbsp;
					menampilkan <?= $batas_atas ?> sampai <?= $batas_bawah ?> dari total <?= $list['total'] ?> data
				</div>
				<nav aria-label="Page navigation example">
					<ul class="pagination pagination-sm">
						<?= $paging ?>
					</ul>
				</nav>
			</div>
		</small>

		<script>
			function goLimit() {
				$("#act").val('list_limit');
				$("#main_form").submit();
			}
		</script>
	<?php
	}

	public static function showPagingCms($paging, $page, $limit_arr, $limit, $list)
	{
		if (!$list['total'])
			return;

		$batas_atas = (($page - 1) * $limit) + 1;
		$batas_bawah = $batas_atas + ($limit - 1);
		if ($batas_bawah > $list['total']) {
			$batas_bawah = $list['total'];
		}
	?>
		<nav>
			<ul class="pagination" style="display:inline">

				<?= $paging ?>

			</ul>&nbsp;&nbsp;
			<?php
			foreach ($limit_arr as $k => $v) {
				$limit_arr1[$v] = $v;
			}
			echo " Perhalaman " . self::createSelect(
				'list_limit',
				$limit_arr1,
				$limit,
				true,
				'dropdown',
				'onchange="goLimit()"
			style="display: inline;
			height: 23px;
			color: #666;
			padding: 4px 4px 4px;
			font-size: 14px;
			background-color: #fff;
			border-radius: 2px;
			-webkit-box-sizing: content-box;
			-moz-box-sizing: content-box;
			box-sizing: content-box;
			margin-top: 0px;
			border-color: #ddd;"'
			);
			?>

			<div style="float:right">Menampilkan <?= $batas_atas ?> sampai <?= $batas_bawah ?> dari <?= $list['total'] ?> data</div>
		</nav>
		<div style="clear:both"></div>
		<script>
			function goLimit() {
				jQuery("#act").val('list_limit');
				jQuery("#main_form").submit();
			}
		</script>
		<?php
	}

	public static function showHeaderCheck($header, $filter_arr, $list_sort, $list_order, $is_filter = true, $is_sort = true, $is_no = true)
	{
		$ci = get_instance();
		if ($is_filter) {
		?>
			<tr id="first-row">
				<?php if ($is_no) { ?>
					<td></td>
				<?php } ?>
				<?php foreach ($header as $rows) {
					if ($rows['field']) {
						$rows['name'] = $rows['field'];
					}
					$edited = true;
					if ($rows['filter'] === false) {
						$edited = $rows['filter'];
						$filter_arr[$rows['name']] = '&nbsp';
					}

					if ($rows['nofilter']) {
						echo "<td style='width:$rows[width];'></td>";
					} else {
						switch ($rows['type']) {
							case 'list':
								echo "<td style='width:$rows[width];'><div class='filter-head'>" . self::createSelect("list_search_filter[" . $rows['name'] . "]", $rows['value'], $filter_arr[$rows['name']], $edited, 'form-control', "style='max-width:$rows[width];'") . "</div></td>";
								break;

							case 'date':
								echo "<td></td>";
								//echo "<td style='position:relative;width:$rows[width];'><div class='filter-head'>".self::createTextBox("list_search[".$rows['name']."]",$filter_arr[$rows['name']],'','',$edited,'form-control datepicker','placeholder="Search '.$rows['label'].'..." '."style='max-width:$rows[width];'")."</div></td>";
								break;

							case 'datetime':
								echo "<td></td>";
								//echo "<td style='position:relative;width:$rows[width];'><div class='filter-head'>".self::createTextBox("list_search[".$rows['name']."]",$filter_arr[$rows['name']],'','',$edited,'form-control datetimepicker','placeholder="Search '.$rows['label'].'..." '."style='max-width:$rows[width];'")."</div></td>";
								break;

							case 'number':
								echo "<td style='position:relative;width:$rows[width];'><div class='filter-head'>" . self::createTextNumber("list_search[" . $rows['name'] . "]", $filter_arr[$rows['name']], '', '', $edited, 'form-control', "style='max-width:$rows[width];'") . "</div></td>";
								break;

							default:
								echo "<td style='width:$rows[width];'><div class='filter-head'>" . self::createTextBox("list_search[" . $rows['name'] . "]", $filter_arr[$rows['name']], '', '', $edited, 'form-control', 'placeholder="Search ' . $rows['label'] . '..." ' . "style='max-width:$rows[width];'") . "</div></td>";
								break;
						}
					}
				}
				?>
				<td style='text-align:left; width:0px'>
					<!-- <button type="submit" class='btn btn-light btn-sm' title="Filter">
						<i class="bi bi-search"></i>
					</button> -->
					<button type="button" class="btn btn-sm btn-light" onclick="goReset()" title="Reset">
						<i class="bi bi-arrow-clockwise"></i>
					</button>
				</td>
			</tr>
		<?php }
		if ($is_sort) {
		?>
			<tr>
				<?php if ($is_no) { ?>
					<th style="width:10px; padding-top: 15px"><?= UI::createCheckBox("checkall", 1, null, null, true, '', 'onclick="checkAll(this)"') ?></th>
				<?php } ?>
				<input type='hidden' name='list_sort' id='list_sort'>
				<input type='hidden' name='list_order' id='list_order'>
				<?php foreach ($header as $rows) {
					if ($rows['nofilter']) {
						echo "<th style='max-width:$rows[width]'>$rows[label]</th>";
					} elseif ($rows['type'] == 'list' or $rows['type'] == 'implodelist') {
						echo "<th style='max-width:$rows[width]'>$rows[label]</th>";
					} else {
						if ($rows['type'] == 'number') {
							$align = "text-align:right;";
						}
						$add_label = $rows['add_label'];

						if ($list_sort == $rows['name']) {
							if (trim($list_order) == 'asc') {
								$order = 'desc';
							} else {
								$order = 'asc';
							}

							if ($add_label) {
								echo "<th style='text-align:left; max-width:$rows[width];' class='sorting_" . $order . "' > $add_label <a href='javascript:void(0)' onclick=\"goSort('{$rows['name']}','$order')\" style='color:#fff;text-decoration:none'>$rows[label]</a> </th>";
							} else {
								echo "<th style='$align max-width:$rows[width]; cursor:pointer;' class='sorting_" . $order . "' onclick=\"goSort('{$rows['name']}','$order')\">$rows[label]</th>";
							}
						} else {
							if ($add_label) {
								echo "<th style='text-align:left; max-width:$rows[width];' class='sorting'> $add_label <a href='javascript:void(0)' onclick=\"goSort('{$rows['name']}','asc')\"style='color:#fff;text-decoration:none'>$rows[label]</a></th>";
							} else {
								echo "<th style='$align max-width:$rows[width]; cursor:pointer;' class='sorting' onclick=\"goSort('{$rows['name']}','asc')\">$rows[label]</th>";
							}
						}
					}
				}
				?>
				<th></th>
			</tr>
		<?php } else { ?>
			<tr>
				<?php if ($is_no) { ?>
					<th style="width:10px">#</th>
				<?php } ?>
				<?php foreach ($header as $rows) {
					echo "<th style='max-width:$rows[width]'>$rows[label]</th>";
				}
				?>
				<th></th>
			</tr>
		<?php } ?>

		<?php if ($is_sort or $is_filter) { ?>
			<script>
				$(function() {
					$("#main_form").submit(function() {
						if ($("#act").val() == '') {
							goSearch();
						}
					});
				});

				function goSort(name, order) {
					$("#list_sort").val(name);
					$("#list_order").val(order);
					$("#act").val('list_sort');
					$("#main_form").submit();
				}

				function goSearch() {
					$("#act").val('list_search');
					$("#main_form").submit();
				}

				function goReset() {
					$("#act").val('list_reset');
					$("#main_form").submit();
				}

				$("#main_form select[name^='list_search_filter'], #main_form input[name^='list_search']").not("#list_limit").change(function() {
					$("#main_form").submit();
				});
			</script>
		<?php
		}
	}

	public static function showHeader($header, $filter_arr, $list_sort, $list_order, $is_filter = true, $is_sort = true, $is_no = true)
	{

		$ci = get_instance();
		if ($is_filter) {
		?>
			<tr id="first-row">
				<?php if ($is_no) { ?>
					<td></td>
				<?php } ?>
				<?php foreach ($header as $rows) {
					if ($rows['field']) {
						$rows['name'] = $rows['field'];
					}
					$edited = true;
					if ($rows['filter'] === false) {
						$edited = $rows['filter'];
						$filter_arr[$rows['name']] = '&nbsp';
					}

					if ($rows['nofilter']) {
						echo "<td style='width:$rows[width];'></td>";
					} else {
						switch ($rows['type']) {
							case 'list':
								echo "<td style='width:$rows[width];'><div class='filter-head'>" . self::createSelect("list_search_filter[" . $rows['name'] . "]", $rows['value'], $filter_arr[$rows['name']], $edited, 'form-control') . "</div></td>";
								break;

							case 'date':
								echo "<td></td>";
								//echo "<td style='position:relative;width:$rows[width];'><div class='filter-head'>".self::createTextBox("list_search[".$rows['name']."]",$filter_arr[$rows['name']],'','',$edited,'form-control datepicker','placeholder="Search '.$rows['label'].'..." '."style='max-width:$rows[width];'")."</div></td>";
								break;

							case 'datetime':
								echo "<td></td>";
								//echo "<td style='position:relative;width:$rows[width];'><div class='filter-head'>".self::createTextBox("list_search[".$rows['name']."]",$filter_arr[$rows['name']],'','',$edited,'form-control datetimepicker','placeholder="Search '.$rows['label'].'..." '."style='max-width:$rows[width];'")."</div></td>";
								break;

							case 'number':
								echo "<td style='position:relative;width:$rows[width];'><div class='filter-head'>" . self::createTextNumber("list_search[" . $rows['name'] . "]", $filter_arr[$rows['name']], '', '', $edited, 'form-control', 'placeholder="Search ' . $rows['label'] . '..." ' . "style='max-width:$rows[width];'") . "</div></td>";
								break;

							default:
								echo "<td style='width:$rows[width];'><div class='filter-head'>" . self::createTextBox("list_search[" . $rows['name'] . "]", $filter_arr[$rows['name']], '', '', $edited, 'form-control', 'placeholder="Search ' . $rows['label'] . '..." ' . "style='max-width:$rows[width];'") . "</div></td>";
								break;
						}
					}
				}
				?>
				<td style='text-align:right; width:5px'>
					<!-- <button type="submit" class='btn btn-light btn-sm' title="Filter">
						<i class="bi bi-search"></i>
					</button> -->
					<button type="button" class="btn btn-light" onclick="goReset()" title="Reset">
						<i class="bi bi-arrow-clockwise"></i>
					</button>
				</td>
			</tr>
		<?php }
		if ($is_sort) {
		?>
			<tr>
				<?php if ($is_no) { ?>
					<th style="width:10px">No</th>
				<?php } ?>
				<input type='hidden' name='list_sort' id='list_sort'>
				<input type='hidden' name='list_order' id='list_order'>
				<?php if ($header)
					foreach ($header as $rows) {
						if ($rows['nofilter'] || $rows['noorder']) {
							echo "<th style='max-width:$rows[width]'>$rows[label]</th>";
						} elseif ($rows['type'] == 'list' or $rows['type'] == 'implodelist') {
							echo "<th style='max-width:$rows[width]'>$rows[label]</th>";
						} else {
							$align = "text-align:left;";
							if ($rows['type'] == 'number') {
								$align = "text-align:right;";
							}
							$add_label = $rows['add_label'];

							if ($list_sort == $rows['name']) {
								if (trim($list_order) == 'asc') {
									$order = 'desc';
								} else {
									$order = 'asc';
								}

								if ($add_label) {
									echo "<th style='text-align:left; max-width:$rows[width];' class='sorting_" . $order . "' ><div class='d-flex'> $add_label <a href='javascript:void(0)' onclick=\"goSort('{$rows['name']}','$order')\" style='color:inherit;text-decoration:none'>$rows[label]</a></div> </th>";
								} else {
									echo "<th style='$align max-width:$rows[width]; cursor:pointer;' class='sorting_" . $order . "' onclick=\"goSort('{$rows['name']}','$order')\">$rows[label]</th>";
								}
							} else {
								if ($add_label) {
									echo "<th style='text-align:left; max-width:$rows[width];' class='sorting'><div class='d-flex'> $add_label <a href='javascript:void(0)' onclick=\"goSort('{$rows['name']}','asc')\"style='color:inherit;text-decoration:none'>$rows[label]</a></div></th>";
								} else {
									echo "<th style='$align max-width:$rows[width]; cursor:pointer;' class='sorting' onclick=\"goSort('{$rows['name']}','asc')\">$rows[label]</th>";
								}
							}
						}
					}
				?>
				<th></th>
			</tr>
		<?php } else { ?>
			<tr>
				<?php if ($is_no) { ?>
					<th style="width:10px">#</th>
				<?php } ?>
				<?php foreach ($header as $rows) {
					if ($rows['type'] == 'number') {
						$align = "text-align:right;";
					} else {
						$align = "text-align:left;";
					}
					echo "<th style='max-width:$rows[width]; $align'>$rows[label]</th>";
				}
				?>
				<th></th>
			</tr>
		<?php } ?>

		<?php if ($is_sort or $is_filter) { ?>
			<script>
				$(function() {
					$("#main_form").submit(function() {
						if ($("#act").val() == '') {
							goSearch();
						}
					});
				});

				function goSort(name, order) {
					$("#list_sort").val(name);
					$("#list_order").val(order);
					$("#act").val('list_sort');
					$("#main_form").submit();
				}

				function goSearch() {
					$("#act").val('list_search');
					$("#main_form").submit();
				}

				function goReset() {
					$("#act").val('list_reset');
					$("#main_form").submit();
				}

				$("#main_form select[name^='list_search_filter'], #main_form input[name^='list_search']").not("#list_limit").change(function() {
					$("#main_form").submit();
				});
			</script>
		<?php
		}
	}

	public static function showHeaderSpiLibrary($header, $filter_arr, $list_sort, $list_order, $is_filter = true, $is_sort = true, $is_no = true)
	{

		$ci = get_instance();
		if ($is_filter) {
		?>
			<tr id="first-row">
				<?php if ($is_no) { ?>
					<td style="width: 5px;"></td>
				<?php } ?>
				<?php foreach ($header as $rows) {
					if ($rows['field']) {
						$rows['name'] = $rows['field'];
					}
					$edited = true;
					if ($rows['filter'] === false) {
						$edited = $rows['filter'];
						$filter_arr[$rows['name']] = '&nbsp';
					}

					if ($rows['nofilter']) {
						echo "<td style='width:$rows[width];'></td>";
					} else {
						switch ($rows['type']) {
							case 'list':
								echo "<td style='width:$rows[width];'><div class='filter-head'>" . self::createSelect("list_search_filter[" . $rows['name'] . "]", $rows['value'], $filter_arr[$rows['name']], $edited, 'form-control') . "</div></td>";
								break;

							case 'date':
								echo "<td></td>";
								//echo "<td style='position:relative;width:$rows[width];'><div class='filter-head'>".self::createTextBox("list_search[".$rows['name']."]",$filter_arr[$rows['name']],'','',$edited,'form-control datepicker','placeholder="Search '.$rows['label'].'..." '."style='max-width:$rows[width];'")."</div></td>";
								break;

							case 'datetime':
								echo "<td></td>";
								//echo "<td style='position:relative;width:$rows[width];'><div class='filter-head'>".self::createTextBox("list_search[".$rows['name']."]",$filter_arr[$rows['name']],'','',$edited,'form-control datetimepicker','placeholder="Search '.$rows['label'].'..." '."style='max-width:$rows[width];'")."</div></td>";
								break;

							case 'number':
								echo "<td style='position:relative;width:$rows[width];'><div class='filter-head'>" . self::createTextNumber("list_search[" . $rows['name'] . "]", $filter_arr[$rows['name']], '', '', $edited, 'form-control', 'placeholder="Search ' . $rows['label'] . '..." ' . "style='max-width:$rows[width];'") . "</div></td>";
								break;

							default:
								echo "<td style='width:$rows[width];'><div class='filter-head'>" . self::createTextBox("list_search[" . $rows['name'] . "]", $filter_arr[$rows['name']], '', '', $edited, 'form-control', 'placeholder="Search ' . $rows['label'] . '..." ' . "style='max-width:$rows[width];'") . "</div></td>";
								break;
						}
					}
				}
				?>
				<td style='text-align:right; width:5px'>
					<!-- <button type="submit" class='btn btn-light btn-sm' title="Filter">
						<i class="bi bi-search"></i>
					</button> -->
					<button type="button" class="btn btn-light" onclick="goReset()" title="Reset">
						<i class="bi bi-arrow-clockwise"></i>
					</button>
				</td>
			</tr>
		<?php }
		?>
		<?php if ($is_sort or $is_filter) { ?>
			<script>
				$(function() {
					$("#main_form").submit(function() {
						if ($("#act").val() == '') {
							goSearch();
						}
					});
				});

				function goSort(name, order) {
					$("#list_sort").val(name);
					$("#list_order").val(order);
					$("#act").val('list_sort');
					$("#main_form").submit();
				}

				function goSearch() {
					$("#act").val('list_search');
					$("#main_form").submit();
				}

				function goReset() {
					$("#act").val('list_reset');
					$("#main_form").submit();
				}

				$("#main_form select[name^='list_search_filter'], #main_form input[name^='list_search']").not("#list_limit").change(function() {
					$("#main_form").submit();
				});
			</script>
		<?php
		}
	}

	public static function showHeaderTree($header, $filter_arr, $list_sort, $list_order, $is_filter = true)
	{

		$ci = get_instance();
		if ($is_filter) {
		?>
			<tr id="first-row">
				<?php foreach ($header as $rows) {
					if ($rows['field']) {
						$rows['name'] = $rows['field'];
					}
					$edited = true;
					if ($rows['filter'] === false) {
						$edited = $rows['filter'];
						$filter_arr[$rows['name']] = '&nbsp';
					}
					switch ($rows['type']) {
						case 'list':
							echo "<td style='width:$rows[width];'><div class='filter-head'>" . self::createSelect("list_search_filter[" . $rows['name'] . "]", $rows['value'], $filter_arr[$rows['name']], $edited, 'form-control', "style='max-width:$rows[width];'") . "</div></td>";
							break;

						case 'date':
							echo "<td style='position:relative;width:$rows[width];'><div class='filter-head'>" . self::createTextBox("list_search[" . $rows['name'] . "]", $filter_arr[$rows['name']], '', '', $edited, 'form-control datepicker', 'placeholder="Search ' . $rows['label'] . '..." ' . "style='max-width:$rows[width];'") . "</div></td>";
							break;

						case 'datetime':
							echo "<td style='position:relative;width:$rows[width];'><div class='filter-head'>" . self::createTextBox("list_search[" . $rows['name'] . "]", $filter_arr[$rows['name']], '', '', $edited, 'form-control datetimepicker', 'placeholder="Search ' . $rows['label'] . '..." ' . "style='max-width:$rows[width];'") . "</div></td>";
							break;

						case 'number':
							echo "<td style='position:relative;width:$rows[width];'><div class='filter-head'>" . self::createTextNumber("list_search[" . $rows['name'] . "]", $filter_arr[$rows['name']], '', '', $edited, 'form-control', "style='max-width:$rows[width];'") . "</div></td>";
							break;

						default:
							echo "<td style='width:$rows[width];'><div class='filter-head'>" . self::createTextBox("list_search[" . $rows['name'] . "]", $filter_arr[$rows['name']], '', '', $edited, 'form-control', 'placeholder="Search ' . $rows['label'] . '..." ' . "style='max-width:$rows[width];'") . "</div></td>";
							break;
					}
				}
				?>
				<td style='text-align:left; width:10px'>
					<!-- <button type="submit" class='btn btn-primary btn-sm'>
						<i class="bi bi-search"></i>
						Filter
					</button>
					<?= self::getButton('reset', null, $add, "btn-sm") ?> -->

					<button type="button" class="btn btn-light" onclick="goReset()" title="Reset">
						<i class="bi bi-arrow-clockwise"></i>
					</button>
				</td>
			</tr>
		<?php } ?>
		<tr>
			<input type='hidden' name='list_sort' id='list_sort'>
			<input type='hidden' name='list_order' id='list_order'>
			<?php foreach ($header as $rows) {
				if ($rows['type'] == 'list' or $rows['type'] == 'implodelist') {
					echo "<th style='max-width:$rows[width]'>$rows[label]</th>";
				} else {
					if ($rows['type'] == 'number') {
						$align = "text-align:right;";
					}

					if ($list_sort == $rows['name']) {
						if (trim($list_order) == 'asc') {
							$order = 'desc';
						} else {
							$order = 'asc';
						}
						echo "<th style='$align max-width:$rows[width]; cursor:pointer;' class='sorting_" . $order . "' onclick=\"goSort('{$rows['name']}','$order')\">$rows[label]</th>";
					} else {
						echo "<th style='$align max-width:$rows[width]; cursor:pointer;' class='sorting' onclick=\"goSort('{$rows['name']}','asc')\">$rows[label]</th>";
					}
				}
			}
			?>
			<th></th>
		</tr>
		<script>
			$(function() {
				$("#main_form").submit(function() {
					if ($("#act").val() == '') {
						goSearch();
					}
				});
			});

			function goSort(name, order) {
				$("#list_sort").val(name);
				$("#list_order").val(order);
				$("#act").val('list_sort');
				$("#main_form").submit();
			}

			function goSearch() {
				$("#act").val('list_search');
				$("#main_form").submit();
			}

			function goReset() {
				$("#act").val('list_reset');
				$("#main_form").submit();
			}

			$("#main_form select[name^='list_search_filter'], #main_form input[name^='list_search']").not("#list_limit").change(function() {
				$("#main_form").submit();
			});
		</script>
	<?php
	}

	public static function showHeaderFix($headerrows, $filter_arr, $list_sort, $list_order, &$header)
	{
		$ci = get_instance();
		if (!$headerrows['rows']) {
			$headerrows['rows'] = array($headerrows);
		}
	?>
		<?php
		foreach ($headerrows['rows'] as $k => $head) {
			echo "<tr>";
			if ($k == 0) {
				echo "<th style='vertical-align:middle' rowspan='" . count($headerrows['rows']) . "'>#</th>";
			}
			foreach ($head as $rows) {

				$add = $rows['add'];

				if ($rows['align'])
					$align = "text-align:{$rows['align']};";

				if ($rows['width'])
					$width = "width:{$rows['width']};";

				if ($rows['type'] !== 'head')
					$header[] = $rows;

				$rowspan = '';
				if ($rows['rowspan'])
					$rowspan = "rowspan='{$rows['rowspan']}'";

				$colspan = '';
				if ($rows['colspan'])
					$colspan = "colspan='{$rows['colspan']}'";

				if ($rows['type'] == 'list' or $rows['type'] == 'head' or $rows['type'] == 'implodelist')
					echo "<th $add $colspan $rowspan style='vertical-align: middle;$width $align'>$rows[label]</th>";
				else {

					$align = $row['align'];

					if ($rows['type'] == 'number')
						$align = "text-align:right;";

					if ($list_sort == $rows['name']) {
						if (trim($list_order) == 'asc')
							$order = 'desc';
						else
							$order = 'asc';

						echo "<th $add $colspan $rowspan style='vertical-align: middle;$width $align cursor:pointer;' class='sorting_" . $order . "' onclick=\"goSort('{$rows['name']}','$order')\">$rows[label]</th>";
					} else
						echo "<th $add $colspan $rowspan style='vertical-align: middle;$width $align cursor:pointer;' class='sorting' onclick=\"goSort('{$rows['name']}','asc')\">$rows[label]</th>";
				}
			}
			echo "</tr>";
		}
		?>

		<tr class="filter-table">
			<td></td>
			<?php foreach ($header as $rows) {
				if ($rows['field']) {
					$rows['name'] = str_replace(".", "_____", $rows['field']);
				}
				$edited = true;
				if ($rows['filter'] === false) {
					$edited = $rows['filter'];
					$filter_arr[$rows['name']] = '&nbsp';
				}
				switch ($rows['type']) {
					case 'list':
						echo "<td style='width:$rows[width];'>" . self::createSelect("list_search_filter[" . $rows['name'] . "]", $rows['value'], $filter_arr[$rows['name']], $edited, 'form-control', "style='width:$rows[width];'") . "</td>";
						break;

					case 'date':
						echo "<td style='position:relative;width:$rows[width];'>" . self::createTextBox("list_search[" . $rows['name'] . "]", $filter_arr[$rows['name']], '', '', $edited, 'form-control datepicker', 'placeholder="Search ' . $rows['label'] . '..." ' . "style='max-width:$rows[width];'") . "</td>";
						break;

					case 'datetime':
						echo "<td style='position:relative;width:$rows[width];'>" . self::createTextBox("list_search[" . $rows['name'] . "]", $filter_arr[$rows['name']], '', '', $edited, 'form-control datetimepicker', 'placeholder="Search ' . $rows['label'] . '..." ' . "style='max-width:$rows[width];'") . "</td>";
						break;

					case 'implodelist':
						echo "<td style='position:relative;width:$rows[width];'></td>";
						break;

					case 'number':
						echo "<td style='position:relative;width:$rows[width];'>" . self::createTextNumber("list_search[" . $rows['name'] . "]", $filter_arr[$rows['name']], '', '', $edited, 'form-control', "style='max-width:$rows[width];'") . "</td>";
						break;

					default:
						echo "<td style='width:$rows[width];'>" . self::createTextBox("list_search[" . $rows['name'] . "]", $filter_arr[$rows['name']], '', '', $edited, 'form-control', 'placeholder="Search ' . $rows['label'] . '..." ' . "style='max-width:$rows[width];'") . "</td>";
						break;
				}
			}
			?>
		</tr>

		<input type='hidden' name='list_sort' id='list_sort'>
		<input type='hidden' name='list_order' id='list_order'>
		<script>
			$(function() {
				$("#main_form").submit(function() {
					if ($("#act").val() == '') {
						goSearch();
					}
				});
			});

			function goSort(name, order) {
				$("#list_sort").val(name);
				$("#list_order").val(order);
				$("#act").val('list_sort');
				$("#main_form").submit();
			}

			function goSearch() {
				$("#act").val('list_search');
				$("#main_form").submit();
			}
		</script>
	<?php
	}


	public static function showHeaderFront($header, $filter_arr, $list_sort, $list_order)
	{
	?>
		<tr>
			<td></td>
			<?php foreach ($header as $rows) {
				switch ($rows['type']) {
					case 'list':
						echo "<td>" . self::createSelect("list_search[" . $rows['name'] . "]", $rows['value'], $filter_arr[$rows['name']], true, 'text_input hint', 'style="width:100%;padding: 6px 0px 6px 10px;" onchange="goSearch()"') . "</td>";
						break;

					default:
						echo "<td>" . self::createTextBox("list_search[" . $rows['name'] . "]", $filter_arr[$rows['name']], '', '', true, 'text_input hint', 'style="width:100%;padding: 6px 0px 6px 10px;" placeholder="Search ' . $rows['label'] . '..."') . "</td>";
						break;
				}
			}
			?>
		</tr>
		<tr>
			<th style="width:10px">#</th>
			<input type='hidden' name='list_sort' id='list_sort'>
			<input type='hidden' name='list_order' id='list_order'>
			<?php foreach ($header as $rows) {
				if ($list_sort == $rows['name']) {
					if (trim($list_order) == 'asc') {
						$order = 'desc';
					} else {
						$order = 'asc';
					}
					echo "<th style='width:$rows[width]'><a href='#' onclick=\"goSort('{$rows['name']}','$order')\">$rows[label]</a></th>";
				} else {
					echo "<th style='width:$rows[width]'><a href='#' onclick=\"goSort('{$rows['name']}','asc')\">$rows[label]</a></th>";
				}
			}
			?>
		</tr>
		<script>
			jQuery(function() {
				jQuery("#main_form").submit(function() {
					if (jQuery("#act").val() == '') {
						goSearch();
					}
				});
			});

			function goSort(name, order) {
				jQuery("#list_sort").val(name);
				jQuery("#list_order").val(order);
				jQuery("#act").val('list_sort');
				jQuery("#main_form").submit();
			}

			function goSearch() {
				jQuery("#act").val('list_search');
				jQuery("#main_form").submit();
			}
		</script>
<?php
	}

	public static function showButtonMode($mode, $idkey = null, $edited = false, $add = '', $class = 'btn-sm', $access_role = null, $page_escape = null)
	{

		$ci = get_instance();

		if (!$access_role)
			$access_role = $ci->access_role;

		$str = '';
		if ($ci->addbuttons && $mode != 'save') {
			foreach ($ci->addbuttons as $k => $value) {
				$str .= self::getButton($value, $idkey, $add, $class, false, false, $access_role, $page_escape);
			}
		}

		if ($ci->buttons && $mode != 'save') {
			foreach ($ci->buttons as $k => $value) {
				$str .= self::getButton($value, $idkey, $add, $class, false, false, $access_role, $page_escape);
			}
			return $str;
		}

		if (strstr($mode, "|") !== false) {
			$modearr = explode("|", $mode);

			if ($modearr) {
				$str = "";
				foreach ($modearr as $v) {
					$str .= self::getButton($v, $idkey, $add, $class, false, false, $access_role, $page_escape);
				}
				return $str;
			}
		}

		if ($mode === 'lst' || $mode === 'index' || $mode === 'daftar') {
			$str .= self::getButton('add', null, $add, $class, false, false, $access_role, $page_escape);

			if ($access_role['list_print'])
				$str .= self::getButton('print', null, $add, $class, false, false, $access_role, $page_escape);

			return $str;
		}

		if ($mode == 'edit_detail') {
			if ($edited)
				$str .= self::getButton('detail', $idkey, $add, $class, 'Detil', false, $access_role, $page_escape);
			else
				$str .= self::getButton('edit', $idkey, $add, $class, false, false, $access_role, $page_escape);


			return $str;
		}
		if ($mode == 'edit_detail_add') {
			if ($edited)
				$str .= self::getButton('detail', $idkey, $add, $class, 'Detil', false, $access_role, $page_escape);
			else
				$str .= self::getButton('edit', $idkey, $add, $class, false, false, $access_role, $page_escape);
			$str .= self::getButton('add', $idkey, $add, $class, false, false, $access_role, $page_escape);


			return $str;
		}

		if ($mode === 'oneedit') {

			$str .= self::getButton('detail', $idkey, $add, $class, false, false, $access_role, $page_escape);
			return $str;
		}

		if ($mode === 'onedetail') {

			$str .= self::getButton('edit', $idkey, $add, $class, false, false, $access_role, $page_escape);
			return $str;
		}

		if ($mode === 'edit') {
			//$str .= self::getButton('save');
			//$str .= self::getButton('batal', $idkey);

			$str .= self::getButton('lst', null, $add, $class, false, false, $access_role, $page_escape);
			$str .= self::getButton('add', null, $add, $class, false, false, $access_role, $page_escape);
			return $str;
		}

		if ($mode === 'add') {
			//$str .= self::getButton('save');
			$str .= self::getButton('lst', null, $add, $class, false, false, $access_role, $page_escape);
			return $str;
		}

		if ($mode === 'detail') {
			$str .= self::getButton('lst', null, $add, $class, false, false, $access_role, $page_escape);
			$str .= self::getButton('add', null, $add, $class, false, false, $access_role, $page_escape);
			$str .= self::getButton('edit', $idkey, $add, $class, false, false, $access_role, $page_escape);
			$str .= self::getButton('delete', $idkey, $add, $class, false, false, $access_role, $page_escape);

			if ($access_role['print_detail'])
				$str .= self::getButton('printdetail', $idkey, $add, $class, false, false, $access_role, $page_escape);

			return $str;
		}

		if ($mode === 'save' && $edited) {
			$str .= self::getButton('save', null, $add, $class, false, false, $access_role, $page_escape);
			$str .= self::getButton('batal', $idkey, $add, $class, false, false, $access_role, $page_escape);
			return $str;
		}

		if ($mode === 'save_back' && $edited) {
			$str .= self::getButton('save', null, $add, $class, false, false, $access_role, $page_escape);
			$str .= self::getButton('lst', $idkey, $add, $class, 'Back', false, $access_role, $page_escape);
			return $str;
		}

		if ($mode === 'save_detail' && $edited) {
			$str .= self::getButton('save', null, $add, $class, false, false, $access_role, $page_escape);
			$str .= self::getButton('detail', $idkey, $add, $class, 'Detil', false, $access_role, $page_escape);
			return $str;
		}

		if ($mode == 'blank') {
			return $str;
		}
	}

	public static function showButtonModeRisiko($mode, $idkey = null, $edited = false, $add = '', $class = '', $access_role = null, $page_escape = null)
	{
		if (!$access_role)
			return false;

		$ci = get_instance();

		$str = '';
		if (($ci->addbuttons)) {
			foreach ($ci->addbuttons as $k => $value) {
				$str .= self::getButton($value, $idkey, $add, $class, false, false, $access_role, $page_escape, $access_role, $page_escape);
			}
		}

		if (($ci->buttons)) {
			foreach ($ci->buttons as $k => $value) {
				$str .= self::getButton($value, $idkey, $add, $class, false, false, $access_role, $page_escape, $access_role, $page_escape);
			}
			return $str;
		}

		if ($mode === 'lst' || $mode === 'index' || $mode === 'daftar') {
			$str .= self::getButton('add', null, $add, $class, false, false, $access_role, $page_escape, $access_role, $page_escape);
			return $str;
		}

		if ($mode === 'oneedit') {

			$str .= self::getButton('detail', $idkey, $add, $class, false, false, $access_role, $page_escape, $access_role, $page_escape);
			return $str;
		}

		if ($mode === 'onedetail') {

			$str .= self::getButton('edit', $idkey, $add, $class, false, false, $access_role, $page_escape, $access_role, $page_escape);
			return $str;
		}

		if ($mode === 'edit') {
			//$str .= self::getButton('save');
			//$str .= self::getButton('batal', $idkey);

			//$str .= self::getButton('lst',null, $add, $class,'List  Risiko', 'goListRisiko()', $access_role, $page_escape);
			//$str .= self::getButton('add',null, $add, $class,'Add  Risiko','goAddRisiko()', $access_role, $page_escape);
			//return $str;
			return;
		}

		if ($mode === 'add') {
			//$str .= self::getButton('save');
			//$str .= self::getButton('lst',null, $add, $class,'List  Risiko', 'goListRisiko()', $access_role, $page_escape);
			//return $str;
			return;
		}

		if ($mode === 'detail') {
			//$str .= self::getButton('lst',null, $add, $class,'List  Risiko', 'goListRisiko()', $access_role, $page_escape);
			//$str .= self::getButton('add',null, $add, $class,'Add  Risiko','goAddRisiko()', $access_role, $page_escape);
			$str .= self::getButton('edit', $idkey, $add, $class, 'Edit  Risiko', 'goEditRisiko()', $access_role, $page_escape);
			$str .= self::getButton('delete', $idkey, $add, $class, 'Delete  Risiko', 'goDeleteRisiko()', $access_role, $page_escape);
			return $str;
		}

		if ($mode === 'save' && $edited) {
			$str .= self::getButton('save', null, $add, $class, false, false, $access_role, $page_escape);
			$str .= self::getButton('batal', $idkey, $add, $class, false, false, $access_role, $page_escape);
			return $str;
		}

		if ($mode == 'blank') {
			return $str;
		}
	}

	public static function Button($array = array())
	{

		$default = array(
			"idkey" => null,
			"add" => '',
			"class" => 'btn-sm',
			"label" => false,
			"action" => false,
			"access_role" => false,
			"page_escape" => false,
		);
		foreach ($default as $idkey => $value) {
			if ($array[$idkey] === null)
				$array[$idkey] = $value;
		}

		return self::getButton($array['id'], $array['idkey'], $array['add'], $array['class'], $array['label'], $array['action'], $array['access_role'], $array['page_escape']);
	}

	public static function getButton($id, $idkey = null, $add = '', $class = 'btn-sm', $label = false, $action = false, $access_role = null, $page_escape = null)
	{

		$ci = get_instance();

		if (!$page_escape)
			$page_escape = array_values($ci->page_escape);

		if (!$access_role)
			$access_role = $ci->access_role;

		$tempid = $id;

		if ($id == 'detail')
			$tempid = 'index';

		if (
			$ci->private == true
			&&
			!$access_role[$id]
			&&
			!in_array($ci->page_ctrl, $page_escape)
			&&
			!in_array($id, $ci->addbuttons)
		) {
			return false;
		}

		if ($ci->data['add_param']) {
			$add_param = '/' . $ci->data['add_param'];
		}

		if ($id === 'add') {
			return ' <button type="button" ' . $add . ' class="btn ' . $class . ' btn-primary" onclick="' . ($action ? $action : 'goAdd()') . '" >' . ($label ? $label : 'Tambah Baru') . '</button> ' . (!$action ? '
			<script>
		    function goAdd(){
		        window.location = "' . base_url($ci->page_ctrl . "/add" . $add_param) . '";
		    }
		    </script>' : '');
		}

		if ($id === 'import') {
			return ' <button type="button" ' . $add . ' class="btn ' . $class . ' btn-primary" onclick="' . ($action ? $action : 'goImport()') . '" ><i class="bi bi-import"></i> ' . ($label ? $label : 'Import') . '</button>' . (!$action ? '
			<script>
		    function goImport(){
		        window.location = "' . base_url($ci->page_ctrl . "/import" . $add_param) . '";
		    }
		    </script>' : '');
		}

		if ($id === 'edit' && $idkey) {
			return ' <button type="button" ' . $add . ' class="btn ' . $class . ' btn-warning" onclick="' . ($action ? $action : 'goEdit(\'' . $idkey . '\')') . '" ><i class="bi bi-pencil"></i> ' . (($label !== false) ? $label : 'Ubah') . '</button> ' . (!$action ? '
			<script>
		    function goEdit(id){
		        window.location = "' . base_url($ci->page_ctrl . "/edit" . $add_param) . '/"+id;
		    }
		    </script>' : '');
		}

		if ($id === 'detail' && $idkey) {
			return ' <button type="button" ' . $add . ' class="btn ' . $class . ' btn-warning" onclick="' . ($action ? $action : 'goDetail(\'' . $idkey . '\')') . '" ><i class="bi bi-eye"></i> ' . ($label ? $label : 'Detil') . '</button> ' . (!$action ? '
			<script>
		    function goDetail(id){
		        window.location = "' . base_url($ci->page_ctrl . "/detail" . $add_param) . '/"+id;
		    }
		    </script>' : '');
		}

		if ($id === 'printdetail') {
			return ' <button type="button" class="btn ' . $class . ' btn-primary" onclick="' . ($action ? $action : 'goPrint(\'' . $idkey . '\')') . '" ><i class="bi bi-printer"></i> ' . ($label ? $label : 'Print') . '</button> ' . (!$action ? '
			<script>
			function goPrint(id){
		        window.open("' . base_url($ci->page_ctrl . "/printdetail" . $add_param) . '/"+id,"_blank");
			}
			</script>' : '');
		}


		if ($id === 'delete' && $idkey) {
			return ' <button type="button" ' . $add . ' class="btn ' . $class . ' btn-danger" onclick="' . ($action ? $action : 'goDelete(\'' . $idkey . '\')') . '" ><i class="bi bi-trash"></i> ' . ($label !== false ? $label : 'Hapus') . '</button> ' . (!$action ? '
			<script>
		    function goDelete(id){
		        if(confirm("Apakah Anda yakin akan menghapus ?")){
		            window.location = "' . base_url($ci->page_ctrl . "/delete" . $add_param) . '/"+id;
		        }
		    }
		    </script>' : '');
		}

		if ($id === 'lst' || $id === 'index') {
			return ' <button type="button" ' . $add . ' class="btn ' . $class . ' btn-light" onclick="' . ($action ? $action : 'goList()') . '" ><i class="bi bi-arrow-left"></i> ' . ($label ? $label : 'Kembali ke Daftar') . '</button>  ' . (!$action ? '
			<script>
			function goList(){
			window.location = "' . base_url($ci->page_ctrl . "/index" . $add_param) . '";
			}
			</script>' : '');
		}

		if ($id === 'save') {
			// if (!isset($_POST['key']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if ($_REQUEST['ismodal'] && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				return "<script>
				$(function(){
					$(\"#btnsavemodal\").html('<button type=\"button\" id=\"btnsavemodal\" class=\"btn btn-success\" onclick=\"$(this).attr(\'disabled\',\'disabled\'); goSaveModalInline(\'save\')\">Save</button>');
				});
				</script>";
			} else {
				return '<button type="submit" class="btn-save btn ' . $class . ' btn-success" onclick="' . ($action ? $action : 'goSave()') . '" ><span class="glyphicon glyphicon-floppy-save"></span> ' . ($label ? $label : 'Save') . '</button>' . (!$action ? '
			<script>
			function goSave(){
				$("#main_form").submit(function(e){
					if(e){
						$(".btn-save").attr("disabled","disabled");
				      	$("#act").val(\'save\');
					}else{
						return false;
					}
				});
			}
			</script>' : '');
			}
		}

		if ($id === 'batal') {
			// if (!isset($_POST['key']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if ($_REQUEST['ismodal'] && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

				if ($access_role['delete'] && $idkey) {
					return "<script>
					$(function(){
						$(\"#btnbackmodal\").html('<a href=\"" . base_url($ci->page_ctrl . "/delete" . $add_param . "/" . $idkey) . "\" class=\"btn btn-danger\" onclick=\"if(confirm(\'Apakah Anda yakin akan menghapus ?\')){return true;}else{return false;}\">Delete</button>');
					});
					</script>";
				} else
					return null;
			} else {
				return '<button type="submit" class="btn waves-effect ' . $class . ' btn-default" onclick="' . ($action ? $action : 'goBatal(\'' . $key . '\')') . '" ><span class="glyphicon glyphicon-repeat"></span> ' . ($label ? $label : 'Reload') . '</button> ' . (!$action ? '
			<script>
			function goBatal(){
				$("#act").val(\'reset\');
				$("#main_form").submit();
			}
			</script>' : '');
			}
		}

		if ($id === 'print') {
			return ' <button type="button" class="btn ' . $class . ' btn-primary" onclick="' . ($action ? $action : 'goPrint(\'' . $idkey . '\')') . '" ><i class="bi bi-printer"></i> ' . ($label ? $label : 'Print') . '</button> ' . (!$action ? '
			<script>
			function goPrint(){
		        $("#act").val("list_search");
				window.open("' . base_url($ci->page_ctrl . "/go_print" . $add_param) . '/?"+$("#main_form").serialize(),"_blank");
			}
			</script>' : '');
		}

		if ($id === 'expportexcel') {
			return '<script src="' . base_url() . 'assets/js/excellentexport.min.js"></script>
			&nbsp;<a download="export-excel.xls" class="btn btn-sm btn-primary" href="#" onclick="return ExcellentExport.excel(this, \'table-export\', \'Export Excel\',\'filter-table\');"><i class="fa fa-file-excel-o"></i> Excel</a>&nbsp;';
		}

		if ($id === 'reset') {
			return ' <button type="button" ' . $add . ' class="btn ' . $class . ' btn-light" onclick="' . ($action ? $action : 'goReset()') . '" ><i class="bi bi-arrow-clockwise"></i> ' . ($label ? $label : 'Reset') . '</button>  ' . (!$action ? '
			<script>
			function goReset(){
				$("#act").val(\'list_reset\');
				$("#main_form").submit();
			}
			</script>' : '');
		}

		if ($id === 'applyfilter') {
			return ' <button type="button" ' . $add . ' class="btn ' . $class . ' btn-warning" onclick="' . ($action ? $action : 'goSearch()') . '" ><i class="bi bi-search"></span>' . ($label ? $label : 'Terapkan Filter') . '</button>  ' . (!$action ? '
			<script>
		    function goSearch(){
		        jQuery("#act").val("list_search");
		        jQuery("#main_form").submit();
		    }
			</script>' : '');
		}

		if ($id === 'filter') {
			return ' <button type="button" ' . $add . ' class="btn ' . $class . ' btn-warning" onclick="' . ($action ? $action : 'goSearch()') . '" ><i class="bi bi-search"></i> ' . ($label ? $label : 'Filter') . '</button>  ' . (!$action ? '
			<script>
		    function goSearch(){
		        jQuery("#act").val("list_filter");
		        jQuery("#main_form").submit();
		    }
			</script>' : '');
		}
	}
	public static function createUploadMultipleMessages($nameid, $value, $page_ctrl, $edit = false, $label = "Select files...")
	{
		$label = "Select files...";
		if ($edit) {
			$ta = '<div id="' . $nameid . 'progress" class="progress" style="display:none">
		        <div class="progress-bar progress-bar-success"></div>
		    </div>';
		}
		$ta .= '<div id="' . $nameid . 'files" class="files read_detail">';

		if ($value['name']) {
			foreach ($value['name'] as $k => $v) {
				if (!@$value['id'][$k])
					continue;

				$k = $value['id'][$k];

				$ta .= "<p class='" . $nameid . $k . " pfile'><a target='_BLANK' href='" . site_url($page_ctrl . "/open_filem/" . $k) . "'></a> ";

				if ($edit)
					$ta .= "<a href='javascript:void(0)' class='btn btn-danger btn-xs' onclick='remove$nameid($k)'>x</a>";

				$ta .= "</p>";

				if ($edit) {
					$ta .= "<input type='hidden' class='" . $nameid . $k . "' name='" . $nameid . "[id][]' value='" . $k . "'/>";
					$ta .= "<input type='hidden' class='" . $nameid . $k . "' name='" . $nameid . "[name][]' value='" . $v . "'/>";
				}
			}
		}

		$ta .= '</div>';

		if ($edit) {
			$ci = get_instance();
			$configfile = $ci->config->item("file_upload_message_config");

			$extstr = $configfile['allowed_types'];
			$max = (round($configfile['max_size'] / 1000)) . " Mb";

			$ta .= '<div id="' . $nameid . 'errors" style="color:red"></div>';
			$ta .= '<span class="label label-upload">Ext : ' . str_replace("|", ",", $extstr) . '</span> &nbsp;&nbsp;&nbsp;';
			$ta .= '<span class="label label-upload">Max : ' . $max . '</span>';

			$ta .= '<br/><span class="btn btn-upload fileinput-button">
		        <i class="glyphicon glyphicon-upload"></i>
		        <span>' . $label . '</span>
		        <input id="' . $nameid . 'upload" name="' . $nameid . 'upload" type="file" multiple>
		    </span>';
		}

		if ($edit) {
			$ta .= "<script>$(function () {
    			'use strict';
			    $('#" . $nameid . "upload').fileupload({
			        url: \"" . site_url($page_ctrl . "/upload_filem") . "\",
			        dataType: 'json',
			        done: function (e, data) {

			            if(data.result.file){
			            	var file = data.result.file;
			                $('<p class=\"" . $nameid . "'+file.id+' pfile\"><a target=\"_BLANK\" href=\"" . site_url($page_ctrl . "/open_filem") . "/'+file.id+'\">'+file.name+'</a> <a href=\"javascript:void(0)\" class=\"btn btn-danger btn-xs\" onclick=\"remove$nameid('+file.id+')\">x</a></p>').appendTo('#" . $nameid . "files');
			                $('<input type=\"hidden\" class=\"" . $nameid . "'+file.id+'\" name=\"" . $nameid . "[id][]\" value=\"'+file.id+'\"><input type=\"hidden\" class=\"" . $nameid . "'+file.id+'\" name=\"" . $nameid . "[name][]\" value=\"'+file.name+'\">').appendTo('#" . $nameid . "files');
				        }

			            if(data.result.error){
			            	var error = data.result.error;
			                $('<p onclick=\"$(this).remove()\">'+error+'</p>').appendTo('#" . $nameid . "errors');
				        }

			            $('#" . $nameid . "progress').hide();
			        },
			        progressall: function (e, data) {
			            $('#" . $nameid . "progress').show();
			            var progress = parseInt(data.loaded / data.total * 100, 10);
			            $('#" . $nameid . "progress .progress-bar').css(
			                'width',
			                progress + '%'
			            );
			        },
			        fail: function(a, data){
	                	$('<p onclick=\"$(this).remove()\">'+data.errorThrown+'</p>').appendTo('#" . $nameid . "errors');
			            $('#" . $nameid . "progress').hide();
			        }
			    }).prop('disabled', !$.support.fileInput)
			        .parent().addClass($.support.fileInput ? undefined : 'disabled');
			});
			function remove$nameid(id){
				if(confirm('Yakin akan menghapus file ini ?')){
					$.ajax({
				        url: \"" . site_url($page_ctrl . "/delete_filem") . "\",
				        data:{id:id,name:'$nameid'},
				        dataType: 'json',
				        type: 'post',
				        success:function(data){
				        	if(data.success)
				        		$('.$nameid'+id).remove();
				        	else
			                	$('<p onclick=\"$(this).remove()\">'+data.error+'</p>').appendTo('#" . $nameid . "errors');

				        },
				        error:function(err){
		                	$('<p onclick=\"$(this).remove()\">'+err.statusText+'</p>').appendTo('#" . $nameid . "errors');
				        }
					});
				}
			}
			</script>";
		}

		return $ta;
	}


	function token_page()
	{
		$ci = get_instance();
		$token_page = substr(md5(microtime()), rand(0, 26), 5);
		$ci->session->SetPage('_token', $token_page);
		return $token_page;
	}

	public static function createForm($rows = array())
	{

		if ($rows['field']) {
			$rows['name'] = $rows['field'];
		}
		$edited = true;

		if (!$rows['width'])
			$rows['width'] = "400px";

		switch ($rows['type']) {
			case 'list':
				$form = self::createSelect("list_search_filter[" . $rows['name'] . "]", $rows['value'], null, $edited, 'form-control', "style='max-width:$rows[width];'");
				break;

			case 'date':
				$form = self::createTextBox("list_search[" . $rows['name'] . "]", null, '', '', $edited, 'form-control datepicker', 'placeholder="Search ' . $rows['label'] . '..." ' . "style='max-width:$rows[width];'");
				break;

			case 'datetime':
				$form = self::createTextBox("list_search[" . $rows['name'] . "]", null, '', '', $edited, 'form-control datetimepicker', 'placeholder="Search ' . $rows['label'] . '..." ' . "style='max-width:$rows[width];'");
				break;

			case 'number':
				$form = self::createTextNumber("list_search[" . $rows['name'] . "]", null, '', '', $edited, 'form-control', "style='max-width:$rows[width];'");
				break;

			default:
				$form = self::createTextBox("list_search[" . $rows['name'] . "]", null, '', '', $edited, 'form-control', 'placeholder="Search ' . $rows['label'] . '..." ' . "style='max-width:$rows[width];'");
				break;
		}

		return $form;
	}

	public static function createStatusRisiko($status_risiko, $edited = false)
	{
		$ci = &get_instance();

		$edited = $edited && $ci->access_role['edit'];

		$row = $ci->data['rowheader1'];

		$riskapertite = $ci->data['riskapertite'];
		$riskmatrixtingkat = $ci->data['riskmatrixtingkat'];

		$tingkat = $riskmatrixtingkat[$row['residual_kemungkinan_evaluasi']][$row['residual_dampak_evaluasi']];

		$edited = (bool)($edited && $ci->access_role['edit']);
		$from = "<div class='d-flex'><b>Status Risiko</b><span class='ms-auto d-flex'>";

		if ($row['id_risiko_sebelum']) {
			$from .= "
        	<a data-bs-toggle='tooltip' data-bs-original-title='Arsip risiko' href='" . site_url("panelbackend/risk_risiko/log_history/$row[id_risiko]") . "' target='_blank'>
        	<i class='bi bi-archive'></i>
        	</a>";
		}

		if ($row['status_keterangan']) {
			$from .= "
        	&nbsp;&nbsp;
        	<a href='javascript:void(0);' data-bs-toggle='tooltip' data-bs-original-title='Keterangan' onclick='$(\"#keterangan_status\").toggle(100)'>
        	<i class='bi bi-sticky'></i>
        	</a>";
		}

		$from .= "<span></div>";
		switch ($status_risiko) {
			case '0':
				$from .= "<span class='badge bg-light text-dark'>CLOSED</span>";
				break;
			case '2':
				$from .= "<span class='badge bg-warning'>BERLANJUT</span>";
				break;

			default:

				if ($row['id_risiko_sebelum']) {
					$from .= "<span class='badge bg-success'>OPEN LANJUTAN</span>";
				} else {
					$from .= "<span class='badge bg-success'>OPEN</span>";
				}
				break;
		}

		$from .= "<style>.iactive{background-color:#3c3950 !important;color:#fff !important;}</style>";

		$from .= "
    	<div style='margin-top:7px'>";

		if ($row['status_keterangan']) {
			$from .= "
        	<div id='keterangan_status' style='display:none; padding-top:0px;'>";
			$from .= $row['status_keterangan'];
			$from .= "</div>";
		}
		$from .= "
        </div>";

		return $from;
	}

	public static function createStatusPeluang($status_peluang, $edited = false)
	{
		$ci = &get_instance();

		$edited = $edited && $ci->access_role['edit'];

		$row = $ci->data['rowheader1'];

		$oppapertite = $ci->data['oppapertite'];
		$oppmatrixtingkat = $ci->data['oppmatrixtingkat'];

		$tingkat = $oppmatrixtingkat[$row['residual_kemungkinan_evaluasi']][$row['residual_dampak_evaluasi']];

		$edited = (bool)($edited && $ci->access_role['edit']);
		$from = "<div class='d-flex'><b>Status Peluang</b><span class='ms-auto d-flex'>";

		if ($row['id_peluang_sebelum']) {
			$from .= "
        	<a data-bs-toggle='tooltip' data-bs-original-title='Arsip peluang' href='" . site_url("panelbackend/opp_peluang/log_history/$row[id_peluang]") . "' target='_blank'>
        	<i class='bi bi-archive'></i>
        	</a>";
		}

		if ($row['status_keterangan']) {
			$from .= "
        	&nbsp;&nbsp;
        	<a href='javascript:void(0);' data-bs-toggle='tooltip' data-bs-original-title='Keterangan' onclick='$(\"#keterangan_status\").toggle(100)'>
        	<i class='bi bi-sticky'></i>
        	</a>";
		}

		$from .= "<span></div>";
		switch ($status_peluang) {
			case '0':
				$from .= "<span class='badge bg-light text-dark'>CLOSED</span>";
				break;
			case '2':
				$from .= "<span class='badge bg-warning'>BERLANJUT</span>";
				break;

			default:

				if ($row['id_peluang_sebelum']) {
					$from .= "<span class='badge bg-success'>OPEN LANJUTAN</span>";
				} else {
					$from .= "<span class='badge bg-success'>OPEN</span>";
				}
				break;
		}

		$from .= "<style>.iactive{background-color:#3c3950 !important;color:#fff !important;}</style>";

		$from .= "
    	<div style='margin-top:7px'>";

		if ($row['status_keterangan']) {
			$from .= "
        	<div id='keterangan_status' style='display:none; padding-top:0px;'>";
			$from .= $row['status_keterangan'];
			$from .= "</div>";
		}
		$from .= "
        </div>";

		return $from;
	}

	public static function createBerlanjut($status_risiko, $edited = false, $id = 'status_risiko')
	{
		$ci = &get_instance();

		$edited = $edited && $ci->access_role['edit'];

		$row = $ci->data['rowheader1'];

		$riskapertite = $ci->data['riskapertite'];
		$riskmatrixtingkat = $ci->data['riskmatrixtingkat'];

		$tingkat = $riskmatrixtingkat[$row['residual_kemungkinan_evaluasi']][$row['residual_dampak_evaluasi']];

		$is_close = (bool)($tingkat <= $riskapertite);

		$edited = (bool)($edited && $ci->access_role['edit']);
		$edited = ($ci->Access('close', 'panelbackend/risk_risiko') && $edited);
		$from = UI::createRadio($id, array("1" => "Open", "0" => "Close"), $status_risiko, $edited);
		return $from;
	}

	#nameid untuk nama halaman (sesuaikan dengan id_pk halaman contoh id_scorecard jadi nameidnya scorecard)
	#value untuk value id_status_pengajuan
	#id untuk id dari halaman yang akan diajukan
	#history untuk history pengajuan2 sebelumnya
	public static function createStatusPengajuan($nameid, $row, $edited = false)
	{

		$value = $row['id_status_pengajuan'];
		$id = $row['id_scorecard'];
		$ci = &get_instance();

		$page = "panelbackend/risk_" . $nameid;

		$rows = $ci->data['task_' . $nameid];


		if (!$id)
			$value = 1;

		$ta = "<div class='d-flex'>" . labelstatus($value) . ($edited ? " <a data-bs-toggle='tooltip' data-bs-original-title='Riwayat pengajuan' class='no-underline ms-auto' href='javascript:void(0);' onclick='$(\"#kettask$nameid\").toggle(100)'><i class='bi bi-clock-history'></i></a>" : null) . "</div>";

		switch ($value) {
			case '1':
			case '4':
				#posisi USER
				if ($ci->Access('pengajuan', $page) && $edited) {
					$ta .= "<br/> <a class='btn btn-sm btn-warning  btn-sm' data-bs-toggle='modal' onclick='$(\"#id_status_pengajuan" . $nameid . "\").val(2)' data-bs-target='#pengajuanmodal" . $nameid . "'> AJUKAN <i class='bi bi-chevron-right'></i></a> ";
				} elseif ($ci->Access('penerusan', $page) && $edited) {
					$ta .= "<br/> <a class='btn btn-sm btn-success  btn-sm' data-bs-toggle='modal' onclick='$(\"#id_status_pengajuan" . $nameid . "\").val(3)' data-bs-target='#pengajuanmodal" . $nameid . "'> AJUKAN KE KOORDINATOR <i class='bi bi-forward'></i></a> ";
				}

				break;
			case '2':
			case '6':
				#posisi KOORDINATOR
				if ($ci->Access('penerusan', $page) && $edited) {
					$ta .= "<br/> <a class='btn btn-sm btn-warning  btn-sm' data-bs-toggle='modal' onclick='$(\"#id_status_pengajuan" . $nameid . "\").val(4)' data-bs-target='#pengajuanmodal" . $nameid . "'><i class='bi bi-chevron-left'></i> KEMBALIKAN </a> ";
					// $ta .= "<br/> <a class='btn btn-sm btn-success  btn-sm' data-bs-toggle='modal' onclick='$(\"#id_status_pengajuan" . $nameid . "\").val(3)' data-bs-target='#pengajuanmodal" . $nameid . "'> AJUKAN KE DIREKSI <i class='bi bi-forward'></i></a> ";
					$ta .= "<br/> <a class='btn btn-sm btn-success  btn-sm' data-bs-toggle='modal' onclick='$(\"#id_status_pengajuan" . $nameid . "\").val(3)' data-bs-target='#pengajuanmodal" . $nameid . "'> TERUSKAN KE KOORDINATOR <i class='bi bi-forward'></i></a> ";
					// $ta .= "<br/> <a class='btn btn-sm btn-primary  btn-sm' data-bs-toggle='modal' onclick='$(\"#id_status_pengajuan" . $nameid . "\").val(5)' data-bs-target='#pengajuanmodal" . $nameid . "'> <i class='bi bi-ok'></i> DISETUJUI </a> ";
				}
				break;
			case '3':
				#posisi OWNER
				if ($ci->Access('persetujuan', $page) && $edited) {
					$ta .= "<br/> <a class='btn btn-sm btn-warning  btn-sm' data-bs-toggle='modal' onclick='$(\"#id_status_pengajuan" . $nameid . "\").val(4)' data-bs-target='#pengajuanmodal" . $nameid . "'><i class='bi bi-chevron-left'></i> KEMBALIKAN </a> ";
					$ta .= "<br/> <a class='btn btn-sm btn-primary  btn-sm' data-bs-toggle='modal' onclick='$(\"#id_status_pengajuan" . $nameid . "\").val(5)' data-bs-target='#pengajuanmodal" . $nameid . "'> <i class='bi bi-ok'></i> DISETUJUI </a> ";
				}
				break;

			case '5':
				#HARUS ADA AKSI EVALUASI
				#posisi USER
				if ($ci->Access('pengajuan', $page) && $edited && $row['is_evaluasi_mitigasi']) {
					// $ta .= "<br/> <a class='btn btn-sm btn-warning  btn-sm' data-bs-toggle='modal' onclick='$(\"#id_status_pengajuan" . $nameid . "\").val(7)' data-bs-target='#pengajuanmodal" . $nameid . "'> AJUKAN EVALUASI <i class='bi bi-chevron-right'></i> </a> ";
				}
				break;
			case '7':
			case '10':
				#HARUS ADA AKSI EVALUASI
				#posisi KOORDINATOR Mitigasi
				if ($ci->Access('evaluasimitigasi', $page) && $edited) {
					$ta .= "<br/> <a class='btn btn-sm btn-primary  btn-sm' data-bs-toggle='modal' onclick='$(\"#id_status_pengajuan" . $nameid . "\").val(5)' data-bs-target='#pengajuanmodal" . $nameid . "'><i class='bi bi-chevron-left'></i> MASIH PERLU PENGENDALIAN/MITIGASI </a> ";
					// if ($row['is_evaluasi_risiko'] || $row['is_evaluasi_peluang'])
					// $ta .= "<br/> <a class='btn btn-sm btn-success  btn-sm' data-bs-toggle='modal' onclick='$(\"#id_status_pengajuan" . $nameid . "\").val(8)' data-bs-target='#pengajuanmodal" . $nameid . "'> TERUSKAN KE SPI <i class='bi bi-chevron-right'></i> </a> ";
					$ta .= "<br/> <a class='btn btn-sm btn-primary  btn-sm' data-bs-toggle='modal' onclick='$(\"#id_status_pengajuan" . $nameid . "\").val(11)' data-bs-target='#pengajuanmodal" . $nameid . "'> <i class='bi bi-ok'></i> DISETUJUI </a> ";
				}
				break;
			case '8':
				#posisi SPI
				if ($ci->Access('evaluasirisiko', $page) && $edited) {
					$ta .= "<br/> <a class='btn btn-sm btn-warning  btn-sm' data-bs-toggle='modal' onclick='$(\"#id_status_pengajuan" . $nameid . "\").val(10)' data-bs-target='#pengajuanmodal" . $nameid . "'><i class='bi bi-chevron-left'></i> KEMBALIKAN KE KOORDINATOR </a> ";
					// $ta .= "<br/> <a class='btn btn-sm btn-success  btn-sm' data-bs-toggle='modal' onclick='$(\"#id_status_pengajuan" . $nameid . "\").val(9)' data-bs-target='#pengajuanmodal" . $nameid . "'> TERUSKAN KE DIREKSI <i class='bi bi-chevron-right'></i> </a> ";
					$ta .= "<br/> <a class='btn btn-sm btn-primary  btn-sm' data-bs-toggle='modal' onclick='$(\"#id_status_pengajuan" . $nameid . "\").val(11)' data-bs-target='#pengajuanmodal" . $nameid . "'> <i class='bi bi-ok'></i> DISETUJUI </a> ";
				}
				break;
			case '9':
				#posisi Direksi
				if ($ci->Access('persetujuan', $page) && $edited) {
					$ta .= "<br/> <a class='btn btn-sm btn-warning  btn-sm' data-bs-toggle='modal' onclick='$(\"#id_status_pengajuan" . $nameid . "\").val(10)' data-bs-target='#pengajuanmodal" . $nameid . "'><i class='bi bi-chevron-left'></i> KEMBALIKAN KE KOORDINATOR </a> ";
					$ta .= "<br/> <a class='btn btn-sm btn-primary  btn-sm' data-bs-toggle='modal' onclick='$(\"#id_status_pengajuan" . $nameid . "\").val(11)' data-bs-target='#pengajuanmodal" . $nameid . "'> <i class='bi bi-ok'></i> DISETUJUI </a> ";
				}
				break;
		}

		if (($ci->Access('pengajuan', $page) or $ci->Access('persetujuan', $page) or $ci->Access('penerusan', $page) or $ci->Access('evaluasimitigasi', $page) or $ci->Access('evaluasirisiko', $page)) && $edited) {
			$ta .= '<div class="modal fade" id="pengajuanmodal' . $nameid . '" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="defaultModalLabel">Keterangan<span style="color:red">*</span></h4>
                        </div>
                        <div class="modal-body"><div class="mb-3 row" style="margin:0px">';
			$ta .= UI::createTextArea('keterangan[' . $nameid . ']', null, '', '', true, $class = 'form-control keterangan' . $nameid, " placeholder='ketik disini untuk menambah keterangan'");
			$ta .= "<input type='hidden' name='id_status_pengajuan[$nameid]' id='id_status_pengajuan" . $nameid . "'/>";
			$ta .= "<input type='hidden' name='id[$nameid]' value='$id'/>";
			$ta .= '</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary  btn-sm" data-bs-dismiss="modal">CLOSE</button>
                            <button type="button" class="btn btn-secondary  btn-sm" onclick="goSubmitRequired(\'task' . $nameid . '\',\'.keterangan' . $nameid . '\')">SEND</button>
                        </div>
                    </div>
                </div>
            </div>';
		}
		if ($rows && $edited) {
			$status_arr = $ci->data['mtstatusarr'];
			$ta .= "<div>
			<div id='kettask$nameid' style='display:none; padding-top:0px;'>";

			// $status_arr[5] = "Menyetujui";
			// $status_arr[3] = "Meneruskan";
			// $status_arr[2] = "Mengajukan";
			// $status_arr[4] = "Mengembalikan";
			// $status_arr[6] = "Mengembalikan";

			foreach ($rows as $r) {
				// $status_arr[$r['id_status_pengajuan']] = str_replace("Di", "Me", $status_arr[$r['id_status_pengajuan']]);

				if (strstr($status_arr[$r['id_status_pengajuan']], "Risiko") === false)
					$status_arr[$r['id_status_pengajuan']] .= " Risiko";

				$ta .= "<b>" . ucwords(strtolower($r['nama_user'])) . " (" . ucwords(strtolower($r['nama_group'])) . ")</b><br/>" . $status_arr[$r['id_status_pengajuan']] . "<br/><i>" . $r['deskripsi'] . "</i> <br/><span style='font-size:10px;color:#777'>" . $r['created_date'] . "</span><hr style='margin:5px 0px;'/>";
			}
			$ta .= "</div></div>";
		}

		return $ta;
	}
	#nameid untuk nama halaman (sesuaikan dengan id_pk halaman contoh id_scorecard jadi nameidnya scorecard)
	#value untuk value id_status_pengajuan
	#id untuk id dari halaman yang akan diajukan
	#history untuk history pengajuan2 sebelumnya
	public static function createKonfirmasi($id, $rows = array(), $status_konfirmasi = null, $edited = false)
	{

		if (!$id)
			return;

		$nameid = 'mitigasi';

		if (!$status_konfirmasi)
			$ta = '<span class="badge bg-warning">DALAM KONFIRMASI</span>';
		elseif ($status_konfirmasi == 1)
			$ta = '<span class="badge bg-success">DISETUJUI</span>';
		elseif ($status_konfirmasi == 2)
			$ta = '<span class="badge bg-danger">DITOLAK</span>';
		elseif ($status_konfirmasi == 3)
			$ta = '<span class="badge bg-info">DELIGASI</span>'; //ke delegasi
		elseif ($status_konfirmasi == 4)
			$ta = '<span class="badge bg-info">APPROVAL</span>'; //ke owner

		// $ta .= "<br/>";
		// $ta .= "<br/>";

		if (!$rows)
			return $ta . "<br/><br/>";

		$ci = &get_instance();

		$edited = (bool)($edited && $ci->access_role['edit']);


		if ($edited && $status_konfirmasi <> 1 && $status_konfirmasi <> 2) {

			if ($ci->post['act'] <> 'chose_delegasi_mitigasi' && !$status_konfirmasi) {
				$ta .= "<br/><br/><a class='btn btn-sm btn-warning  btn-sm' data-bs-toggle='modal' onclick='$(\"#id_status_pengajuan" . $nameid . "\").val(4)' data-bs-target='#pengajuanmodal" . $nameid . "'><i class='bi bi-chevron-left'></i> TOLAK </a> ";
				$ta .= " <a class='btn btn-sm btn-primary  btn-sm' onclick='goSubmit(\"chose_delegasi_mitigasi\")'> <i class='bi bi-share'></i> DELIGASI </a>";
				$ta .= " <a class='btn btn-sm btn-primary  btn-sm' onclick='goSubmit(\"approve_mitigasi\")'> <i class='bi bi-ok'></i> SETUJUI </a>";

				$ta .= '<div class="modal fade" id="pengajuanmodal' . $nameid . '" tabindex="-1" role="dialog">
		                <div class="modal-dialog" role="document">
		                    <div class="modal-content">
		                        <div class="modal-header">
		                            <h4 class="modal-title" id="defaultModalLabel">Keterangan</h4>
		                        </div>
		                        <div class="modal-body"><div class="mb-3 row" style="margin:0px">';
				$ta .= UI::createTextArea('keterangan[' . $nameid . ']', null, '', '', true, $class = 'form-control', " placeholder='ketik disini untuk menambah keterangan'");
				$ta .= "<input type='hidden' name='id_status_pengajuan[$nameid]' id='id_status_pengajuan" . $nameid . "'/>";
				$ta .= "<input type='hidden' name='id[$nameid]' value='$id'/>";
				$ta .= '</div>
		                        </div>
		                        <div class="modal-footer">
		                            <button type="button" class="btn btn-secondary  btn-sm" data-bs-dismiss="modal">CLOSE</button>
		                            <button type="button" class="btn btn-secondary  btn-sm" onclick="goSubmit(\'task' . $nameid . '\')">SEND</button>
		                        </div>
		                    </div>
		                </div>
		            </div>';
			} else {
				if (!$status_konfirmasi) {
					$ta .= "<b>Delegasi : </b>" . UI::createSelect('interdependent_delegasi', $ci->data['delegasiarr'], $ci->post['interdependent_delegasi']);
					$ta .= " <a class='btn btn-sm btn-primary  btn-sm' onclick='goSubmit(\"delegasi_mitigasi\")'> <i class='bi bi-ok'></i> TUNJUK </a>";
					$ta .= " <a class='btn btn-sm btn-light  btn-sm' onclick='goSubmit(\"reset\")'> <i class='bi bi-ok'></i> BATAL </a>";
				} elseif ($status_konfirmasi == 3 && $ci->data['row']['interdependent_delegasi'] == $_SESSION[SESSION_APP]['id_jabatan']) {
					$ta .= " <br/><a class='btn btn-sm btn-primary  btn-sm' onclick='goSubmit(\"ajukan_mitigasi\")'> <i class='bi bi-ok'></i> AJUKAN KE ATASAN</a>";
				} elseif ($status_konfirmasi == 4 && $_SESSION[SESSION_APP]['id_jabatan'] == $ci->data['row']['penanggung_jawab']) {
					$ta .= " <br/><a class='btn btn-sm btn-primary  btn-sm' onclick='goSubmit(\"kembalikan_mitigasi\")'> <i class='bi bi-arrow-back'></i> KEMBALIKAN </a>";
					$ta .= " <a class='btn btn-sm btn-primary  btn-sm' onclick='goSubmit(\"approve_mitigasi\")'> <i class='bi bi-ok'></i> SETUJUI </a>";
				}
			}
		}

		if ($rows) {
			$ta .= "<div style='margin-top: 7px;'>
        	<a href='javascript:void(0);' onclick='$(\"#kettask$nameid\").toggle(100)'>RIWAYAT PERSETUJUAN</a><div id='kettask$nameid' style='display:none; padding-top:0px;'>";
			foreach ($rows as $r) {
				$ta .= "Dari <b>" . ucwords(strtolower($r['nama_user'])) . "</b> ke <b>" . ucwords(strtolower($r['jabatan_penerima'])) . "</b><br/><i>" . $r['deskripsi'] . "</i><hr style='margin:5px 0px;'/>";
			}
			$ta .= "</div></div>";
		}

		return $ta;
	}

	public static function tingkatEfektitifitasControl($id_pengukuran)
	{
		$ci = &get_instance();
		$rowspengukuran = $ci->data['rowspengukuran'];

		$pengukuranarr = array();
		foreach ($rowspengukuran as $r) {
			$pengukuranarr[$r['id_pengukuran']] = "<span class='badge bg-$r[warna]' style='background-color:$r[warna]; color:#000000;'>{$r['efektifitas']}</span>";
		}

		return $pengukuranarr[$id_pengukuran];
	}

	public static function tingkatPeluang($idkemungkinan, $iddampak, $data, $edited, $is_lg = true)
	{
		$nkemungkinan = (int)$data[$idkemungkinan];
		$ndampak = (int)$data[$iddampak];

		$ci = &get_instance();
		$mtoppmatrixarr = $ci->data['mtoppmatrixarr'];

		$matrixarr = array();
		foreach ($mtoppmatrixarr as $r) {
			$res = (int)$r['rating_kemungkinan'] * (int)$r['rating_dampak'];
			$res = abs($res);
			if ($is_lg)
				$matrixarr[$r['id_dampak']][$r['id_kemungkinan']] = "<h4 style='display:inline'><span class='badge bg-$r[warna]' style='background-color:$r[warna]; color:#000000;'>{$res}</span></h4>";
			else
				$matrixarr[$r['id_dampak']][$r['id_kemungkinan']] = "<span class='badge bg-$r[warna]' style='background-color:$r[warna]; color:#000000;'>{$res}</span>";
		}

		if ($edited)
			$nameid = $idkemungkinan . "span";
		else
			$nameid = "span";

		$tingkat = $matrixarr[$ndampak][$nkemungkinan];

		$str = '';

		if ($tingkat)
			$str .= "<span id='$nameid'>$tingkat</span>";
		elseif ($is_lg)
			$str .= "<span id='$nameid'></span>";
		else
			$str .= "<i><small>(-)</small></i>";


		if ($edited) {
			$str .= "<script>
			function $nameid(k,d){ ";
			foreach ($matrixarr as $k => $rows) {
				foreach ($rows as $k1 => $v) {
					$str .= "
					if(k==$k1 && d==$k){
						return \"" . $matrixarr[$k][$k1] . "\";
					}";
				}
			}
			$str .= "
			}
			$(function(){

			$('#$idkemungkinan, #$iddampak').change(function(){
				var k = $('#$idkemungkinan').val();
				var d = $('#$iddampak').val();
				var nameid = $nameid(k,d);
				$('#$nameid').html(nameid);
			});
			});
			</script>";
		}

		return $str;
	}

	public static function tingkatRisiko($idkemungkinan, $iddampak, $data, $edited, $is_lg = false)
	{
		$nkemungkinan = (int)$data[$idkemungkinan];
		$ndampak = (int)$data[$iddampak];

		$ci = &get_instance();
		$mtriskmatrixarr = $ci->data['mtriskmatrixarr'];
		$risk_opp = $ci->data['risk_opp'];
		// $riskopp = $data['is_opp_inherent'] && $ci->page_ctrl == 'panelbackend/risk_analisis' ? $data['is_opp_inherent'] : null;
		$riskopp = $data['is_opp_inherent'] ? $data['is_opp_inherent'] : null;

		$matrixarr = array();
		if ($riskopp)
			foreach ($risk_opp as $f) {
				foreach ($mtriskmatrixarr as $r) {
					$res = (int)$r['rating_kemungkinan'] * (int)$r['rating_dampak'];
					if ($riskopp) {
						$res = abs($res * $f);
					}
					if ($f === 1) {
						if ($is_lg)
							$matrixarr[$r['id_dampak']][$r['id_kemungkinan']][$f] = "<h4 style='display:inline'><span class='badge bg-$r[warna_peluang]' style='background-color:$r[warna_peluang]; color:#000000;'>{$res}</span></h4>";
						else
							$matrixarr[$r['id_dampak']][$r['id_kemungkinan']][$f] = "<span class='badge bg-$r[warna_peluang]' style='background-color:$r[warna_peluang]; color:#000000;'>{$res}</span>";
					} else {
						if ($is_lg)
							$matrixarr[$r['id_dampak']][$r['id_kemungkinan']][$f] = "<h4 style='display:inline'><span class='badge bg-$r[warna]' style='background-color:$r[warna]; color:#000000;'>{$res}</span></h4>";
						else
							$matrixarr[$r['id_dampak']][$r['id_kemungkinan']][$f] = "<span class='badge bg-$r[warna]' style='background-color:$r[warna]; color:#000000;'>" . $r['rating_kemungkinan'] * $r['rating_dampak'] . "</span>";
					}
				}
			}
		else
			foreach ($mtriskmatrixarr as $r) {
				// if ($is_lg)
				// 	$matrixarr[$r['id_dampak']][$r['id_kemungkinan']] = "<h4 style='display:inline'><span class='badge bg-$r[warna]' style='background-color:$r[warna]; color:#000000;'>{$r['rating_kemungkinan']}{$r['rating_dampak']}</span></h4>";
				// else
				// 	$matrixarr[$r['id_dampak']][$r['id_kemungkinan']] = "<span class='badge bg-$r[warna]' style='background-color:$r[warna]; color:#000000;'>{$res}</span>";

				$res = (int)$r['rating_kemungkinan'] * (int)$r['rating_dampak'];
				if ($is_lg)
					$matrixarr[$r['id_dampak']][$r['id_kemungkinan']] = "<h4 style='display:inline'><span class='badge bg-$r[warna]' style='background-color:$r[warna]; color:#000000;'>{$res}</span></h4>";
				else
					$matrixarr[$r['id_dampak']][$r['id_kemungkinan']] = "<span class='badge bg-$r[warna]' style='background-color:$r[warna]; color:#000000;'>{$res}</span>";
			}


		// dpr($matrixarr, 1);

		if ($edited)
			$nameid = $idkemungkinan . "span";
		else
			$nameid = "span";

		if ($riskopp)
			$tingkat = $matrixarr[$ndampak][$nkemungkinan][$riskopp];
		else
			$tingkat = $matrixarr[$ndampak][$nkemungkinan];

		$str = '';


		if ($tingkat)
			$str .= "<span id='$nameid'>$tingkat</span>";
		elseif ($is_lg)
			$str .= "<span id='$nameid'></span>";
		else
			$str .= "<i><small>(-)</small></i>";


		if ($edited) {
			$str .= "<script>
			function $nameid(k,d,op){ ";
			if ($riskopp)
				foreach ($matrixarr as $k => $rows) {
					foreach ($rows as $k1 => $v) {
						foreach ($risk_opp as $f) {
							$str .= "
					if(k==$k1 && d==$k && op==$f){
						console.log(op)
						return \"" . $matrixarr[$k][$k1][$f] . "\";
					}";
						}
					}
				}
			else
				foreach ($matrixarr as $k => $rows) {
					foreach ($rows as $k1 => $v) {
						$str .= "
					if(k==$k1 && d==$k){
						return \"" . $matrixarr[$k][$k1] . "\";
					}";
					}
				}
			$str .= "
			}
			$(function(){

			// $(" . "\"input[name=is_opp_inherent],#$idkemungkinan, #$iddampak).change(function(){
				$(" . "\"input[name=is_opp_inherent],#$idkemungkinan, #$iddampak\").change(function(){
				var k = $('#$idkemungkinan').val();
				var d = $('#$iddampak').val();
				var op = $('input[name=is_opp_inherent]:checked').val();
				var opp = " . ((int)($data['is_opp_inherent'])) . ";
				if(op){
					op = op
				} else { op = opp}
				var nameid = $nameid(k,d,op);
				$('#$nameid').html(nameid);
			});
			});
			</script>";
		}

		return $str;
	}

	public static function createInfo($id = null, $title = null, $text = null, $class = "model-xs", $is_plain = false)
	{

		$ret = '<div class="modal fade" id="' . $id . '" tabindex="-1" role="dialog">
                <div class="modal-dialog ' . $class . '" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="' . $id . 'Label" style="color: #333;">
                            ' . $title . '
                            </h4>
                        </div>
                        <div class="modal-body">
                        ' . $text . '
						</div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary  btn-sm" data-bs-dismiss="modal">CLOSE</button>
                        </div>
                    </div>
                </div>
            </div>';
		if ($is_plain) {
			$ret .= '<a href="javascript:void()" data-bs-toggle="modal" style="color:#fff" data-bs-target="#' . $id . '"><i class="bi bi-info"></i></a>';
		} else {
			echo $ret;
			$ret = '<button type="button" class="btn btn-plain  btn-sm" data-bs-toggle="modal" data-bs-target="#' . $id . '"><i class="bi bi-info"></i></button>';
		}


		return $ret;
	}
	public static function showMenuMode($mode, $idkey = null, $edited = false, $add = '', $class = '', $access_role = null, $page_escape = null, $addmenu = array())
	{

		$ci = get_instance();

		if (!$access_role)
			$access_role = $ci->access_role;

		$str = self::startMenu($mode == 'inlist');

		if (is_array($ci->addbuttons) && count($ci->addbuttons) && $mode != 'save') {
			foreach ($ci->addbuttons as $k => $value) {
				$str .= self::getMenu($value, $idkey, $add, $class, false, false, $access_role, $page_escape);
			}
		}

		if (is_array($ci->buttons) && count($ci->buttons) && $mode != 'save') {
			foreach ($ci->buttons as $k => $value) {
				$str .= self::getMenu($value, $idkey, $add, $class, false, false, $access_role, $page_escape);
			}

			$str .= '</ul></div>';

			return $str;
		}

		if (is_array($addmenu) && count($addmenu)) {
			foreach ($addmenu as $k => $v) {
				$str .= $v;
			}
		}

		if (strstr($mode, "|") !== false) {
			$modearr = explode("|", $mode);

			if (count($modearr)) {
				$str = "";
				foreach ($modearr as $v) {
					$str .= self::getMenu($v, $idkey, $add, $class, false, false, $access_role, $page_escape);
				}

				$str .= '</ul></div>';

				return $str;
			}
		}

		switch ($mode) {
			case 'inlist':
				$str .= self::getMenu('edit', $idkey, $add, $class, false, false, $access_role, $page_escape);
				$str .= self::getMenu('delete', $idkey, $add, $class, false, false, $access_role, $page_escape);
				break;
			case 'printdetail':
				$str .= self::getMenu('printdetail', $idkey, $add, $class, false, false, $access_role, $page_escape);
				$str .= self::getMenu('edit', $idkey, $add, $class, false, false, $access_role, $page_escape);
				$str .= self::getMenu('delete', $idkey, $add, $class, false, false, $access_role, $page_escape);
				break;
			case 'catatan':
				$str .= self::getMenu('catatan', $idkey, $add, $class, false, false, $access_role, $page_escape);
				break;
			case 'lst':
			case 'index':
			case 'index':
				return self::getButton('add', null, $add, $class, false, false, $access_role, $page_escape);
				break;
			case 'edit_detail':
				if ($edited)
					$str .= "";
				else
					$str .= self::getMenu('edit', $idkey, $add, $class, false, false, $access_role, $page_escape);
				break;
			case 'oneedit':
				$str .= self::getMenu('detail', $idkey, $add, $class, false, false, $access_role, $page_escape);
				break;
			case 'onedetail':
				$str .= self::getMenu('edit', $idkey, $add, $class, false, false, $access_role, $page_escape);
				break;
			case 'import':
				$str .= self::getMenu('import', $idkey, $add, $class, false, false, $access_role, $page_escape);
				break;
			case 'edit':
				$str .= self::getMenu('add', null, $add, $class, false, false, $access_role, $page_escape);
				$str .= self::getMenu('delete', $idkey, $add, $class, false, false, $access_role, $page_escape);
				break;
			case 'add':
				return '';
				break;
			case 'detail':
				$str .= self::getMenu('add', null, $add, $class, false, false, $access_role, $page_escape);
				$str .= self::getMenu('edit', $idkey, $add, $class, false, false, $access_role, $page_escape);
				$str .= self::getMenu('delete', $idkey, $add, $class, false, false, $access_role, $page_escape);
				break;
			case 'blank':
				return '';
				break;
		}

		$str .= self::closeMenu();

		return $str;
	}

	public static function getMenu($id, $idkey = null, $add = '', $class = '', $label = false, $action = false, $access_role = null, $page_escape = null)
	{

		if (strstr($idkey, "/") == false)
			$idkey = urlencode($idkey);
		$ci = get_instance();

		if (!$page_escape)
			$page_escape = array_values($ci->page_escape);

		if (!$access_role)
			$access_role = $ci->access_role;

		$tempid = $id;

		if ($id == 'detail')
			$tempid = 'index';

		if (
			$ci->private == true
			&&
			!$access_role[$id]
			&&
			!in_array($ci->page_ctrl, $page_escape)
			&&
			!in_array($id, $ci->addbuttons)
		) {
			return false;
		}

		if (!$access_role[$id])
			return false;

		if ($ci->data['add_param']) {
			$add_param = '/' . $ci->data['add_param'];
		}

		$class .= " dropdown-item ";


		if ($id === 'add') {
			return ' <li><a href="javascript:void(0)" ' . $add . ' class="' . $class . '" onclick="' . ($action ? $action : 'goAdd()') . '" ><i class="bi bi-plus"></i> ' . ($label ? $label : 'Add New') . '</a> </li> ' . (!$action ? '
			<script>
		    function goAdd(){
		        window.location = "' . base_url($ci->page_ctrl . "/add" . $add_param) . '";
		    }
		    </script>' : '');
		}

		if ($id === 'import') {
			return '<button type="button" ' . $add . ' class="btn ' . $class . ' btn-primary" onclick="' . ($action ? $action : 'goImport()') . '" ><i class="bi bi-import"></i> ' . ($label ? $label : 'Import') . '</a> </li>' . (!$action ? '
			<script>
		    function goImport(){
		        window.location = "' . base_url($ci->page_ctrl . "/import" . $add_param) . '";
		    }
		    </script>' : '');
		}

		if ($id === 'edit' && $idkey) {
			return '<li><a href="javascript:void(0)" ' . $add . ' class="' . $class . '" onclick="' . ($action ? $action : 'goEdit(\'' . $idkey . '\')') . '" ><i class="bi bi-pencil"></i> ' . (($label !== false) ? $label : 'Edit') . '</a> </li>' . (!$action ? '
			<script>
		    function goEdit(id){
		        window.location = "' . base_url($ci->page_ctrl . "/edit" . $add_param) . '/"+id;
		    }
		    </script>' : '');
		}
		if ($id === 'catatan' && $idkey) {
			return '<li><a href="javascript:void(0)" ' . $add . ' class="' . $class . '" onclick="' . ($action ? $action : 'goEdit(\'' . $idkey . '\')') . '" ><i class="bi bi-pencil"></i> ' . (($label !== false) ? $label : 'Catatan') . '</a> </li>' . (!$action ? '
			<script>
		    function goEdit(id){
		        window.location = "' . base_url($ci->page_ctrl . "/edit" . $add_param) . '/"+id;
		    }
		    </script>' : '');
		}

		if ($id === 'detail' && $idkey) {
			return '<li><a href="javascript:void(0)" ' . $add . ' class="' . $class . '" onclick="' . ($action ? $action : 'goDetail(\'' . $idkey . '\')') . '" ><i class="bi bi-eye"></i> ' . ($label ? $label : 'Detil') . '</a> </li> ' . (!$action ? '
			<script>
		    function goDetail(id){
		        window.location = "' . base_url($ci->page_ctrl . "/detail" . $add_param) . '/"+id;
		    }
		    </script>' : '');
		}
		if ($id === 'printdetail' && $idkey) {
			return '<li><a href="javascript:void(0)" ' . $add . ' class="' . $class . '" onclick="' . ($action ? $action : 'goPrint(\'' . $idkey . '\')') . '" ><i class="bi bi-eye"></i> ' . ($label ? $label : 'Print') . '</a> </li> ' . (!$action ? '
			<script>
		    function goPrint(id){
		        window.open("' . base_url($ci->page_ctrl . "/printdetail" . $add_param) . '/"+id,"_blank");
			}
		    </script>' : '');
		}
		// if ($id === 'printdetail') {
		// 	return ' <button type="button" class="btn ' . $class . ' btn-primary" onclick="' . ($action ? $action : 'goPrint(\'' . $idkey . '\')') . '" ><i class="bi bi-printer"></i> ' . ($label ? $label : 'Print') . '</button> ' . (!$action ? '
		// 	<script>
		// 	function goPrint(id){
		//         window.open("' . base_url($ci->page_ctrl . "/printdetail" . $add_param) . '/"+id,"_blank");
		// 	}
		// 	</script>' : '');
		// }
		if ($id === 'delete' && $idkey) {
			return '<li><a href="javascript:void(0)" ' . $add . ' class="' . $class . '" onclick="' . ($action ? $action : 'goDelete(\'' . $idkey . '\')') . '" ><i class="bi bi-trash"></i> ' . ($label !== false ? $label : 'Delete') . '</a> </li>' . (!$action ? '
			<script>
		    function goDelete(id){
		        if(confirm("Apakah Anda yakin akan menghapus ?")){
		            window.location = "' . base_url($ci->page_ctrl . "/delete" . $add_param) . '/"+id;
		        }
		    }
		    </script>' : '');
		}

		if ($id === 'lst' || $id === 'index') {
			return '<li><a href="javascript:void(0)" ' . $add . ' class="' . $class . '" onclick="' . ($action ? $action : 'goList()') . '" ><i class="bi bi-arrow-left"></i> ' . ($label ? $label : 'Back') . '</a> </li>  ' . (!$action ? '
			<script>
			function goList(){
			window.location = "' . base_url($ci->page_ctrl . "/index" . $add_param) . '";
			}
			</script>' : '');
		}

		if ($id === 'save') {
			return '<li><a href="javascript:void(0)" ' . $add . ' class="' . $class . '" onclick="' . ($action ? $action : 'goSave()') . '" ><i class="bi bi-upload"></i> ' . ($label ? $label : 'Save') . '</a> </li>' . (!$action ? '
			<script>
			function goSave(){
				$("#main_form").submit(function(e){
					if(e){
						$(".btn-save").attr("disabled","disabled");
				      	$("#act").val(\'save\');
					}else{
						return false;
					}
				});
			}
			</script>' : '');
		}

		if ($id === 'batal') {
			return '<li><a href="javascript:void(0)" ' . $add . ' class="' . $class . '" onclick="' . ($action ? $action : 'goBatal(\'' . $idkey . '\')') . '" ><i class="bi bi-arrow-clockwise"></i> ' . ($label ? $label : 'Reload') . '</a> </li>' . (!$action ? '
			<script>
			function goBatal(){
				$("#act").val(\'reset\');
				$("#main_form").submit();
			}
			</script>' : '');
		}

		if ($id === 'print') {
			return '<li><a href="javascript:void(0)" ' . $add . ' class="' . $class . '" onclick="' . ($action ? $action : 'goPrint(\'' . $idkey . '\')') . '" ><i class="bi bi-printer"></i> ' . ($label ? $label : 'Print') . '</a> </li> ' . (!$action ? '
			<script>
			function goPrint(){
		        $("#act").val("list_search");
				window.open("' . base_url($ci->page_ctrl . "/go_print" . $add_param) . '/?"+$("#main_form").serialize(),"_blank");
			}
			</script>' : '');
		}

		if ($id === 'expportexcel') {
			return '<script src="' . base_url() . 'assets/js/excellentexport.min.js"></script>
			&nbsp;<a download="export-excel.xls" class="btn btn-sm btn-primary" href="#" onclick="return ExcellentExport.excel(this, \'table-export\', \'Export Excel\',\'filter-table\');"><i class="bi bi-export"></i> Excel</a>&nbsp;';
		}

		if ($id === 'reset') {
			return '<li><a href="javascript:void(0)" ' . $add . ' class="' . $class . '" onclick="' . ($action ? $action : 'goReset()') . '" ><i class="bi bi-arrow-clockwise"></i> ' . ($label ? $label : 'Reset') . '</a> </li>  ' . (!$action ? '
			<script>
			function goReset(){
				$("#act").val(\'list_reset\');
				$("#main_form").submit();
			}
			</script>' : '');
		}

		if ($id === 'applyfilter') {
			return '<li><a href="javascript:void(0)" ' . $add . ' class="' . $class . '" onclick="' . ($action ? $action : 'goSearch()') . '" ><i class="bi bi-search"></i>' . ($label ? $label : 'Terapkan Filter') . '</a> </li>  ' . (!$action ? '
			<script>
		    function goSearch(){
		        jQuery("#act").val("list_search");
		        jQuery("#main_form").submit();
		    }
			</script>' : '');
		}

		if ($id === 'filter') {
			return '<li><a href="javascript:void(0)" ' . $add . ' class="' . $class . '" onclick="' . ($action ? $action : 'goSearch()') . '" ><i class="bi bi-search"></i> ' . ($label ? $label : 'Filter') . '</a> </li> ' . (!$action ? '
			<script>
		    function goSearch(){
		        jQuery("#act").val("list_filter");
		        jQuery("#main_form").submit();
		    }
			</script>' : '');
		}
	}

	public static function startMenu($xs = false)
	{
		$str = '<div class="dropdown" style="display:inline">';
		if ($xs) {
			$str .= '
					<a href="javascript:void(0)" class="dropdown" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="color:#0052cc;display:inline-block;">';
		} else {
			$str .= '
					<a href="javascript:void(0)" class="dropdown" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="color:#0052cc;padding: 5px;line-height:1.5;display:inline-block;">';
		}
		$str .= '
						<i class="bi bi-three-dots-vertical"></i>
					</a>
					<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenu2" style="margin-top:-20px">';

		return $str;
	}
	public static function closeMenu()
	{
		return "</ul></div>";
	}

	public static function showBack($mode, $idkey = null, $edited = false, $add = '', $class = '', $access_role = null, $page_escape = null)
	{

		switch ($mode) {
			case 'edit':
				return self::getButton('lst', null, $add, $class . ' btn-sm', false, false, $access_role, $page_escape);
				break;
			case 'add':
				return self::getButton('lst', null, $add, $class . ' btn-sm', false, false, $access_role, $page_escape);
				break;
			case 'detail':
				return self::getButton('lst', null, $add, $class . ' btn-sm', false, false, $access_role, $page_escape);
				break;
		}

		return '';
	}



	public static function createUploadMultiple($nameid, $value, $page_ctrl, $edit = false, $label = "Select files...", $add = null, $addfn = null)
	{
		$nameid = str_replace([".", " "], "", $nameid);
		$label = "Select files...";
		if (!$edit)
			$nameid .= 'readonly';
		if ($edit) {
			$ta = '<div id="' . $nameid . 'progress" class="progress" style="display:none">
		        <div class="progress-bar progress-bar-success"></div>
		    </div>';
		}
		$ta .= '<div id="' . $nameid . 'files" class="files read_detail">';

		if ($value['name']) {
			foreach ($value['name'] as $k => $v) {
				if (!@$value['id'][$k])
					continue;

				$k = $value['id'][$k];

				$ta .= "<p class='" . $nameid . $k . " pfile'><a target='_BLANK' href='" . site_url($page_ctrl . "/open_file/" . $k) . "'>$v</a> ";

				if ($edit)
					$ta .= "<a href='javascript:void(0)' class='btn btn-danger btn-sm' onclick='remove$nameid($k)'>x</a>";

				$ta .= "</p>";

				if ($edit) {
					$ta .= "<input type='hidden' class='" . $nameid . $k . "' name='" . $nameid . "[id][]' value='" . $k . "'/>";
					$ta .= "<input type='hidden' class='" . $nameid . $k . "' name='" . $nameid . "[name][]' value='" . $v . "'/>";

					$ta .= "<input type='hidden' name='" . $nameid . "old[id][]' value='" . $k . "'/>";
					$ta .= "<input type='hidden' name='" . $nameid . "old[name][]' value='" . $v . "'/>";
				}
			}
		}

		$ta .= '</div>';

		if ($edit) {
			$ci = get_instance();
			$configfile = $ci->config->item("file_upload_config");

			$extstr = $configfile['allowed_types'];
			$max = (round($configfile['max_size'] / 1000)) . " Mb";

			$ta .= '<div id="' . $nameid . 'errors" style="color:red"></div>';
			$ta .= '<span class="badge bg-upload">Ext : ' . str_replace("|", ",", $extstr) . '</span> &nbsp;&nbsp;&nbsp;';
			$ta .= '<span class="badge bg-upload">Max : ' . $max . '</span>';

			$ta .= '<br/><span class="btn btn-upload fileinput-button">
		        <i class="bi bi-upload"></i>
		        <span>' . $label . '</span>
		        <input id="' . $nameid . 'upload" name="' . $nameid . 'upload" type="file" multiple>
		    </span>';
		}

		if ($edit) {

			// $add = null;
			if ($ci->data['row'][$ci->pk])
				$add .= "/" . $ci->data['row'][$ci->pk];

			$ta .= "<script>$(function () {
    			'use strict';
			    $('#" . $nameid . "upload').fileupload({
			        url: \"" . site_url($page_ctrl . "/upload_file" . $add) . "\",
			        dataType: 'json',
			        done: function (e, data) {

			            if(data.result.file){
			            	var file = data.result.file;
			                $('<p class=\"" . $nameid . "'+file.id+' pfile\"><a target=\"_BLANK\" href=\"" . site_url($page_ctrl . "/open_file") . "/'+file.id+'\">'+file.name+'</a> <a href=\"javascript:void(0)\" class=\"btn btn-danger btn-sm\" onclick=\"remove$nameid('+file.id+')\">x</a></p>').appendTo('#" . $nameid . "files');
			                $('<input type=\"hidden\" class=\"" . $nameid . "'+file.id+'\" name=\"" . $nameid . "[id][]\" value=\"'+file.id+'\"><input type=\"hidden\" class=\"" . $nameid . "'+file.id+'\" name=\"" . $nameid . "[name][]\" value=\"'+file.name+'\">').appendTo('#" . $nameid . "files');
				        }

			            if(data.result.error){
			            	var error = data.result.error;
			                $('<p onclick=\"$(this).remove()\">'+error+'</p>').appendTo('#" . $nameid . "errors');
						}
						
						if(data.result.errors){
			            	var error = data.result.errors;
			                $('<p onclick=\"$(this).remove()\">'+error+'</p>').appendTo('#" . $nameid . "errors');
						}

						" . ($addfn ? $addfn : null) . "


			            $('#" . $nameid . "progress').hide();
			        },
			        progressall: function (e, data) {
			            $('#" . $nameid . "progress').show();
			            var progress = parseInt(data.loaded / data.total * 100, 10);
			            $('#" . $nameid . "progress .progress-bar').css(
			                'width',
			                progress + '%'
			            );
			        },
			        fail: function(a, data){
	                	$('<p onclick=\"$(this).remove()\">'+data.errorThrown+'</p>').appendTo('#" . $nameid . "errors');
			            $('#" . $nameid . "progress').hide();
			        }
			    }).prop('disabled', !$.support.fileInput)
			        .parent().addClass($.support.fileInput ? undefined : 'disabled');
			});
			function remove$nameid(id){
				if(confirm('Yakin akan menghapus file ini ?')){
					$.ajax({
				        url: \"" . site_url($page_ctrl . "/delete_file") . "\",
				        data:{id:id,name:'$nameid'},
				        dataType: 'json',
				        type: 'post',
				        success:function(data){
				        	if(data.success)
				        		$('.$nameid'+id).remove();
				        	else
			                	$('<p onclick=\"$(this).remove()\">'+data.error+'</p>').appendTo('#" . $nameid . "errors');

								" . ($addfn ? $addfn : null) . "
				        },
				        error:function(err){
		                	$('<p onclick=\"$(this).remove()\">'+err.statusText+'</p>').appendTo('#" . $nameid . "errors');
				        }
					});
				}
			}
			</script>";
		}

		return $ta;
	}

	public static function createUploadDirectory($nameid, $value, $page_ctrl, $edit = false, $label = "Select folder...", $add = null, $addfn = null)
	{
		$nameid = str_replace([".", " "], "", $nameid);
		$label = "Select folder...";
		if ($edit) {
			$ta = '<div id="' . $nameid . 'progress" class="progress" style="display:none">
		        <div class="progress-bar progress-bar-success"></div>
		    </div>';
		}
		$ta .= '<div id="' . $nameid . 'files" class="files read_detail">';

		if ($value['name']) {
			foreach ($value['name'] as $k => $v) {
				if (!@$value['id'][$k])
					continue;

				$k = $value['id'][$k];

				$ta .= "<p class='" . $nameid . $k . " pfile'><a target='_BLANK' href='" . site_url($page_ctrl . "/open_file/" . $k) . "'>$v</a> ";

				if ($edit)
					$ta .= "<a href='javascript:void(0)' class='btn btn-danger btn-sm' onclick='remove$nameid($k)'>x</a>";

				$ta .= "</p>";

				if ($edit) {
					$ta .= "<input type='hidden' class='" . $nameid . $k . "' name='" . $nameid . "[id][]' value='" . $k . "'/>";
					$ta .= "<input type='hidden' class='" . $nameid . $k . "' name='" . $nameid . "[name][]' value='" . $v . "'/>";

					$ta .= "<input type='hidden' name='" . $nameid . "old[id][]' value='" . $k . "'/>";
					$ta .= "<input type='hidden' name='" . $nameid . "old[name][]' value='" . $v . "'/>";
				}
			}
		}

		$ta .= '</div>';

		if ($edit) {
			$ci = get_instance();
			$configfile = $ci->config->item("file_upload_config");

			$extstr = $configfile['allowed_types'];
			$max = (round($configfile['max_size'] / 1000)) . " Mb";

			$ta .= '<div id="' . $nameid . 'errors" style="color:red"></div>';
			$ta .= '<span class="badge bg-upload">Ext : ' . str_replace("|", ",", $extstr) . '</span> &nbsp;&nbsp;&nbsp;';
			$ta .= '<span class="badge bg-upload">Max : ' . $max . '</span>';

			$ta .= '<br/><span class="btn btn-upload fileinput-button">
		        <i class="bi bi-upload"></i>
		        <span>' . $label . '</span>
		        <input id="' . $nameid . 'upload" name="' . $nameid . 'upload" type="file" multiple webkitdirectory mozdirectory>
		    </span>';
		}

		if ($edit)
			$ta .= "<input type='hidden' name='" . $nameid . "folder' id='" . $nameid . "folder'/>";

		if ($edit) {

			// $add = null;
			if ($ci->data['row'][$ci->pk])
				$add .= "/" . $ci->data['row'][$ci->pk];

			$ta .= "<script>$(function () {
				
    			'use strict';
			    $('#" . $nameid . "upload').fileupload({
			        url: \"" . site_url($page_ctrl . "/upload_file" . $add) . "\",
			        dataType: 'json',
					add : function (e, data) {
						var file = data.files[0].webkitRelativePath;
						$('#" . $nameid . "folder').val(file);					
						if (e.isDefaultPrevented()) {
							return false;
						}
						if (data.autoUpload || (data.autoUpload !== false &&
								$(this).fileupload('option', 'autoUpload'))) {
							data.process().done(function () {
								data.submit();
							});
						}
					},
			        done: function (e, data) {

			            if(data.result.file){
			            	var file = data.result.file;
			                $('<p class=\"" . $nameid . "'+file.id+' pfile\"><a target=\"_BLANK\" href=\"" . site_url($page_ctrl . "/open_file") . "/'+file.id+'\">'+file.name+'</a> <a href=\"javascript:void(0)\" class=\"btn btn-danger btn-sm\" onclick=\"remove$nameid('+file.id+')\">x</a></p>').appendTo('#" . $nameid . "files');
			                $('<input type=\"hidden\" class=\"" . $nameid . "'+file.id+'\" name=\"" . $nameid . "[id][]\" value=\"'+file.id+'\"><input type=\"hidden\" class=\"" . $nameid . "'+file.id+'\" name=\"" . $nameid . "[name][]\" value=\"'+file.name+'\">').appendTo('#" . $nameid . "files');
				        }

			            if(data.result.error){
			            	var error = data.result.error;
			                $('<p onclick=\"$(this).remove()\">'+error+'</p>').appendTo('#" . $nameid . "errors');
						}
						
						if(data.result.errors){
			            	var error = data.result.errors;
			                $('<p onclick=\"$(this).remove()\">'+error+'</p>').appendTo('#" . $nameid . "errors');
						}

						" . ($addfn ? $addfn : null) . "


			            $('#" . $nameid . "progress').hide();
			        },
			        progressall: function (e, data) {
			            $('#" . $nameid . "progress').show();
			            var progress = parseInt(data.loaded / data.total * 100, 10);
			            $('#" . $nameid . "progress .progress-bar').css(
			                'width',
			                progress + '%'
			            );
			        },
			        fail: function(a, data){
	                	$('<p onclick=\"$(this).remove()\">'+data.errorThrown+'</p>').appendTo('#" . $nameid . "errors');
			            $('#" . $nameid . "progress').hide();
			        }
			    }).prop('disabled', !$.support.fileInput)
			        .parent().addClass($.support.fileInput ? undefined : 'disabled');
			});
			function remove$nameid(id){
				if(confirm('Yakin akan menghapus file ini ?')){
					$.ajax({
				        url: \"" . site_url($page_ctrl . "/delete_file") . "\",
				        data:{id:id,name:'$nameid'},
				        dataType: 'json',
				        type: 'post',
				        success:function(data){
				        	if(data.success)
				        		$('.$nameid'+id).remove();
				        	else
			                	$('<p onclick=\"$(this).remove()\">'+data.error+'</p>').appendTo('#" . $nameid . "errors');

								" . ($addfn ? $addfn : null) . "
				        },
				        error:function(err){
		                	$('<p onclick=\"$(this).remove()\">'+err.statusText+'</p>').appendTo('#" . $nameid . "errors');
				        }
					});
				}
			}
			</script>";
		}

		return $ta;
	}

	public static function createUpload($nameid, $value, $page_ctrl, $edit = false, $label = "Select files...", $allowed_types = null)
	{
		// dpr($value, 1);
		// $value['name'] = 'tessssss';
		$label = "Select files...";
		$nameid1 = str_replace(array("[", "]"), "", $nameid);

		if ($edit) {
			$ta = '<div id="' . $nameid1 . 'progress" class="progress" style="display:none">
		        <div class="progress-bar progress-bar-success"></div>
		    </div>';
		}
		$ta .= '<div id="' . $nameid1 . 'files" class="files">';

		if ($value['name']) {

			$k = $value['id'];
			$v = $value['name'];

			$ta .= "<p class='" . $nameid1 . $k . " pfile'><a target='_BLANK' href='" . site_url($page_ctrl . "/open_file/" . $k) . "'>$v</a> ";

			if ($edit)
				$ta .= "<a href='javascript:void(0)' class='btn btn-danger btn-sm' onclick='remove$nameid1($k)'>x</a>";

			$ta .= "</p>";

			if ($edit) {
				$ta .= "<input type='hidden' class='" . $nameid1 . $k . "' name='" . $nameid . "[id]' value='" . $k . "'/>";
				$ta .= "<input type='hidden' class='" . $nameid1 . $k . "' name='" . $nameid . "[name]' value='" . $v . "'/>";
			}
		}

		$ta .= '</div>';

		if ($edit) {
			$ci = get_instance();
			$configfile = $ci->config->item("file_upload_config");

			if ($allowed_types)
				$extstr = $allowed_types;
			else
				$extstr = $configfile['allowed_types'];

			$max = (round($configfile['max_size'] / 1000)) . " Mb";

			$ta .= '<div id="' . $nameid1 . 'errors" style="color:red"></div>';
			$ta .= '<div id="btn' . $nameid1 . '"';

			if ($value['name']) {
				$ta .= " style='display:none' ";
			}

			$ta .= '><span class="badge bg-upload">Ext : ' . str_replace("|", ",", $extstr) . '</span> &nbsp;&nbsp;&nbsp;';
			$ta .= '<span class="badge bg-upload">Max : ' . $max . '</span>';

			$ta .= '<br/><span class="btn btn-upload fileinput-button">
		        <i class="bi bi-upload"></i>
		        <span>' . $label . '</span>
		        <input id="' . $nameid1 . 'upload" name="' . $nameid1 . 'upload" type="file">
		    </span></div>';
		}

		if ($edit) {

			$add = null;
			if ($ci->data['row'][$ci->pk])
				$add = "/" . $ci->data['row'][$ci->pk];

			$ta .= "<script>
				$(function () {";

			if ($value['name']) {
				$ta .= "$('#btn" . $nameid1 . "').hide();";
			}

			$ta .= "
    			'use strict';
			    $('#" . $nameid1 . "upload').fileupload({
			        url: \"" . site_url($page_ctrl . "/upload_file" . $add) . "\",
			        dataType: 'json',
			        done: function (e, data) {
			        	$('#" . $nameid1 . "errors').html('');
			            if(data.result.file){
		        			$('#btn" . $nameid1 . "').hide();
			            	var file = data.result.file;
			                $('<p class=\"" . $nameid1 . "'+file.id+' pfile\"><a target=\"_BLANK\" href=\"" . site_url($page_ctrl . "/open_file") . "/'+file.id+'\">'+file.name+'</a> <a href=\"javascript:void(0)\" class=\"btn btn-danger btn-sm\" onclick=\"remove$nameid1('+file.id+')\">x</a></p>').appendTo('#" . $nameid1 . "files');
			                $('<input type=\"hidden\" class=\"" . $nameid1 . "'+file.id+'\" name=\"" . $nameid . "[id]\" value=\"'+file.id+'\"><input type=\"hidden\" class=\"" . $nameid1 . "'+file.id+'\" name=\"" . $nameid . "[name]\" value=\"'+file.name+'\">').appendTo('#" . $nameid1 . "files');
				        }

			            if(data.result.error){
		        			$('#btn" . $nameid1 . "').show();
			            	var error = data.result.error;
			                $('<p onclick=\"$(this).remove()\">'+error+'</p>').appendTo('#" . $nameid1 . "errors');
				        }

			            $('#" . $nameid1 . "progress').hide();
			        },
			        progressall: function (e, data) {
			            $('#" . $nameid1 . "progress').show();
			            var progress = parseInt(data.loaded / data.total * 100, 10);
			            $('#" . $nameid1 . "progress .progress-bar').css(
			                'width',
			                progress + '%'
			            );
		        		$('#btn" . $nameid1 . "').hide();
			        },
			        fail: function(a, data){
			        	$('#" . $nameid1 . "errors').html('');
	                	$('<p onclick=\"$(this).remove()\">'+data.errorThrown+'</p>').appendTo('#" . $nameid1 . "errors');
			            $('#" . $nameid1 . "progress').hide();
		        		$('#btn" . $nameid1 . "').show();
			        }
			    }).prop('disabled', !$.support.fileInput)
			        .parent().addClass($.support.fileInput ? undefined : 'disabled');
			});";

			$ta .= "
			function remove$nameid1(id){
				if(confirm('Yakin akan menghapus file ini ?')){
					$.ajax({
				        url: \"" . site_url($page_ctrl . "/delete_file") . "\",
				        data:{id:id,name:'$nameid1'},
				        dataType: 'json',
				        type: 'post',
				        success:function(data){
			        		$('#" . $nameid1 . "errors').html('');
				        	if(data.success){
				        		$('.$nameid1'+id).remove();
				        		$('#btn" . $nameid1 . "').show();
				        	}
				        	else{
			                	$('<p onclick=\"$(this).remove()\">'+data.error+'</p>').appendTo('#" . $nameid1 . "errors');
				        		$('#btn" . $nameid1 . "').hide();
				        	}

				        },
				        error:function(err){
			        		$('#" . $nameid1 . "errors').html('');
			        		$('#btn" . $nameid1 . "').hide();
		                	$('<p onclick=\"$(this).remove()\">'+err.statusText+'</p>').appendTo('#" . $nameid1 . "errors');
				        }
					});
				}
			}
			</script>";
		}

		return $ta;
	}

	public static function createUploadSpiLibrary($nameid, $value, $page_ctrl, $edit = false, $label = "Select files...", $allowed_types = null)
	{
		// dpr($value, 1);
		// $value['name'] = 'tessssss';
		$label = "Select files...";
		$nameid1 = str_replace(array("[", "]"), "", $nameid);

		if ($edit) {
			$ta = '<div id="' . $nameid1 . 'progress" class="progress" style="display:none">
		        <div class="progress-bar progress-bar-success"></div>
		    </div>';
		}
		$ta .= '<div id="' . $nameid1 . 'files" class="files">';

		if ($value['name']) {

			$k = $value['id'];
			$v = $value['name'];

			$ta .= "<p class='" . $nameid1 . $k . " pfile'><a target='_BLANK' href='" . site_url($page_ctrl . "/open_file/" . $k) . "'>$v</a> ";

			if ($edit)
				$ta .= "<a href='javascript:void(0)' class='btn btn-danger btn-sm' onclick='remove$nameid1($k)'>x</a>";

			$ta .= "</p>";

			if ($edit) {
				$ta .= "<input type='hidden' class='" . $nameid1 . $k . "' name='" . $nameid . "[id]' value='" . $k . "'/>";
				$ta .= "<input type='hidden' class='" . $nameid1 . $k . "' name='" . $nameid . "[name]' value='" . $v . "'/>";
			}
		}

		$ta .= '</div>';

		if ($edit) {
			$ci = get_instance();
			$configfile = $ci->config->item("file_upload_config");

			if ($allowed_types)
				$extstr = $allowed_types;
			else
				$extstr = $configfile['allowed_types'];

			$max = (round($configfile['max_size'] / 1000)) . " Mb";

			$ta .= '<div id="' . $nameid1 . 'errors" style="color:red"></div>';
			$ta .= '<div id="btn' . $nameid1 . '"';

			if ($value['name']) {
				$ta .= " style='display:none' ";
			}

			$ta .= '><span class="badge bg-upload">Ext : ' . str_replace("|", ",", $extstr) . '</span> &nbsp;&nbsp;&nbsp;';
			$ta .= '<span class="badge bg-upload">Max : ' . $max . '</span>';

			$ta .= '<br/><span class="btn btn-upload fileinput-button">
		        <i class="bi bi-upload"></i>
		        <span>' . $label . '</span>
		        <input id="' . $nameid1 . 'upload" name="' . $nameid1 . 'upload" type="file">
		    </span></div>';
		}

		if ($edit) {

			$add = null;
			if ($ci->data['row'][$ci->pk])
				$add = "/" . $ci->data['row'][$ci->pk];

			$ta .= "<script>
				$(function () {";

			if ($value['name']) {
				$ta .= "$('#btn" . $nameid1 . "').hide();";
			}

			$ta .= "
    			'use strict';
			    $('#" . $nameid1 . "upload').fileupload({
			        url: \"" . site_url($page_ctrl . "/upload_file" . $add) . "\",
			        dataType: 'json',
			        done: function (e, data) {
			        	$('#" . $nameid1 . "errors').html('');
			            if(data.result.file){
		        			$('#btn" . $nameid1 . "').hide();
			            	var file = data.result.file;
			                $('<p class=\"" . $nameid1 . "'+file.id+' pfile\"><a target=\"_BLANK\" href=\"" . site_url($page_ctrl . "/open_file/'+file.id+'") . "\">'+file.name+'</a> <a href=\"javascript:void(0)\" class=\"btn btn-danger btn-sm\" onclick=\"remove$nameid1('+file.id+')\">x</a></p>').appendTo('#" . $nameid1 . "files');
			                $('<input type=\"hidden\" class=\"" . $nameid1 . "'+file.id+'\" name=\"" . $nameid . "[id]\" value=\"'+file.id+'\"><input type=\"hidden\" class=\"" . $nameid1 . "'+file.id+'\" name=\"" . $nameid . "[name]\" value=\"'+file.name+'\">').appendTo('#" . $nameid1 . "files');
				        }

			            if(data.result.error){
		        			$('#btn" . $nameid1 . "').show();
			            	var error = data.result.error;
			                $('<p onclick=\"$(this).remove()\">'+error+'</p>').appendTo('#" . $nameid1 . "errors');
				        }

			            $('#" . $nameid1 . "progress').hide();
			        },
			        progressall: function (e, data) {
			            $('#" . $nameid1 . "progress').show();
			            var progress = parseInt(data.loaded / data.total * 100, 10);
			            $('#" . $nameid1 . "progress .progress-bar').css(
			                'width',
			                progress + '%'
			            );
		        		$('#btn" . $nameid1 . "').hide();
			        },
			        fail: function(a, data){
			        	$('#" . $nameid1 . "errors').html('');
	                	$('<p onclick=\"$(this).remove()\">'+data.errorThrown+'</p>').appendTo('#" . $nameid1 . "errors');
			            $('#" . $nameid1 . "progress').hide();
		        		$('#btn" . $nameid1 . "').show();
			        }
			    }).prop('disabled', !$.support.fileInput)
			        .parent().addClass($.support.fileInput ? undefined : 'disabled');
			});";

			$ta .= "
			function remove$nameid1(id){
				if(confirm('Yakin akan menghapus file ini ?')){
					$.ajax({
				        url: \"" . site_url($page_ctrl . "/delete_file") . "\",
				        data:{id:id,name:'$nameid1'},
				        dataType: 'json',
				        type: 'post',
				        success:function(data){
			        		$('#" . $nameid1 . "errors').html('');
				        	if(data.success){
				        		$('.$nameid1'+id).remove();
				        		$('#btn" . $nameid1 . "').show();
				        	}
				        	else{
			                	$('<p onclick=\"$(this).remove()\">'+data.error+'</p>').appendTo('#" . $nameid1 . "errors');
				        		$('#btn" . $nameid1 . "').hide();
				        	}

				        },
				        error:function(err){
			        		$('#" . $nameid1 . "errors').html('');
			        		$('#btn" . $nameid1 . "').hide();
		                	$('<p onclick=\"$(this).remove()\">'+err.statusText+'</p>').appendTo('#" . $nameid1 . "errors');
				        }
					});
				}
			}
			</script>";
		}

		return $ta;
	}


	public static function createExportImport($nameid = "import", $value = null, $page_ctrl = null, $edit = true)
	{
		$nameid1 = str_replace(array("[", "]"), "", $nameid);

		$ci = get_instance();

		if (!$page_ctrl)
			$page_ctrl = $ci->page_ctrl;

		if (!$ci->access_role['add'])
			return;

		if ($ci->data['add_param'])
			$add = "/" . $ci->data['add_param'];

		$pk = $ci->model->pk;

		if ($ci->modeldetail->pk) {
			$add .= "/" . $ci->data['row'][$pk];
			$pk = $ci->modeldetail->pk;
		}

		$ta = '<div id="' . $nameid1 . 'progress" class="progress" style="display:none">
	        <div class="progress-bar progress-bar-success"> Loading .... </div>
	    </div>';

		$ta .= '<div id="' . $nameid1 . 'errors" style="color:red"></div>';

		$ta .= '<div id="btn' . $nameid1 . '">
		<button style="padding: 0px;line-height: 0;font-size: 24px;" type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#myModal' . $nameid1 . '">
<i class="bi bi-info"></i>
</button>

<div class="modal fade" id="myModal' . $nameid1 . '" tabindex="-1" role="dialog" aria-labelledby="myModal' . $nameid1 . 'Label" style="text-align:left">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModal' . $nameid1 . 'Label">Petunjuk Import</h4>
		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul>
        	<li>Download template dengan cara export data.</li>';
		// <li>Jika data baru, kolom <b>' . $pk . '</b> silahkan dikosongi.</li>';
		$header = $ci->HeaderExport();
		foreach ($header as $r) {
			if ($r['required']) {
				$ta .= "<li>$r[label] wajib diisi.</li>";
			}
			if ($r['type'] == 'list') {
				$ta .= "<li>$r[label] harus diisi dengan kode berikut:<br/>";
				$temparr = array();
				unset($r['value']['']);
				foreach ($r['value'] as $k => $v) {
					$temparr[] = "$k : $v";
				}
				$ta .= implode("<br/>", $temparr);
				$ta .= "</li>";
			}
			if ($r['type'] == 'listinverst') {
				$ta .= "<li>$r[label] harus diisi dengan kode berikut:<br/>";
				$temparr = array();
				unset($r['value']['']);
				foreach ($r['value'] as $k => $v) {
					$temparr[] = "- $v";
				}
				$ta .= implode("<br/>", $temparr);
				$ta .= "</li>";
			}
		}
		$ta .= '</li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>	
      </div>
    </div>
  </div>
</div>
		';
		if ($ci->access_role['export_list']) {
			$ta .= '
		<button class="btn btn-export btn-link fileinput-button" type="button" onclick="window.location=\'' . site_url($page_ctrl . "/export_list" . $add) . '\'">
	        <i class="bi bi-download"></i>
		Export
		</button>';
		}
		if ($ci->access_role['import_list']) {
			$ta .= '
		<span class="btn btn-import btn-link fileinput-button">
	        <i class="bi bi-upload"></i>
	        <span>Import Data</span>
	        <input id="' . $nameid1 . 'upload" name="' . $nameid1 . 'upload" type="file">
	    </span>';
		}
		$ta .= '</div>';

		$ta .= "<script>
			$(function () {
			'use strict';
		    $('#" . $nameid1 . "upload').fileupload({
		        url: \"" . site_url($page_ctrl . "/import_list" . $add) . "\",
		        dataType: 'json',
		        done: function (e, data) {
		        	$('#" . $nameid1 . "errors').html('');
		            if(data.result.success){
	        			$('#btn" . $nameid1 . "').hide();
		            	window.location='';
			        }

		            if(data.result.error){
	        			$('#btn" . $nameid1 . "').show();
		            	var error = data.result.error;
		                $('<p onclick=\"$(this).remove()\">'+error+'</p>').appendTo('#" . $nameid1 . "errors');
			        }

		            $('#" . $nameid1 . "progress').hide();
		        },
		        progressall: function (e, data) {
		            $('#" . $nameid1 . "progress').show();
		            var progress = parseInt(data.loaded / data.total * 100, 10);
		            $('#" . $nameid1 . "progress .progress-bar').css(
		                'width',
		                progress + '%'
		            );
	        		$('#btn" . $nameid1 . "').hide();
		        },
		        fail: function(a, data){
		        	$('#" . $nameid1 . "errors').html('');
                	$('<p onclick=\"$(this).remove()\">'+data.errorThrown+'</p>').appendTo('#" . $nameid1 . "errors');
		            $('#" . $nameid1 . "progress').hide();
	        		$('#btn" . $nameid1 . "').show();
		        }
		    }).prop('disabled', !$.support.fileInput)
		        .parent().addClass($.support.fileInput ? undefined : 'disabled');
		});
		</script><div style='clear:both;margin-bottom:5px'></div>";

		return $ta;
	}

	public static function createExportImportReap($nameid = "import", $value = null, $page_ctrl = null, $edit = true)
	{
		$nameid1 = str_replace(array("[", "]"), "", $nameid);

		$ci = get_instance();

		if (!$page_ctrl)
			$page_ctrl = $ci->page_ctrl;

		if (!$ci->access_role['add'])
			return;

		if ($ci->data['add_param'])
			$add = "/" . $ci->data['add_param'];

		$pk = $ci->model->pk;

		if ($ci->modeldetail->pk) {
			$add .= "/" . $ci->data['row'][$pk];
			$pk = $ci->modeldetail->pk;
		}

		$ta = '<div id="' . $nameid1 . 'progress" class="progress" style="display:none">
	        <div class="progress-bar progress-bar-success"> Loading .... </div>
	    </div>';

		$ta .= '<div id="' . $nameid1 . 'errors" style="color:red"></div>';

		$ta .= '<div id="btn' . $nameid1 . '">
		<button style="padding: 0px;line-height: 0;font-size: 24px;" type="button" class="btn btn-primary btn-lg  btn-link" data-bs-toggle="modal" data-bs-target="#myModal' . $nameid1 . '">
<i class="bi bi-info"></i>
</button>

<div class="modal fade" id="myModal' . $nameid1 . '" tabindex="-1" role="dialog" aria-labelledby="myModal' . $nameid1 . 'Label" style="text-align:left">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModal' . $nameid1 . 'Label">Petunjuk Import</h4>
      </div>
      <div class="modal-body">
        <ul>
        	<li>Download template dengan cara export data.</li>';
		// <li>Jika data baru, kolom <b>' . $pk . '</b> silahkan dikosongi.</li>';
		$header = $ci->HeaderExport();
		foreach ($header as $r) {
			if ($r['required']) {
				$ta .= "<li>$r[label] wajib diisi.</li>";
			}
			if ($r['type'] == 'list') {
				$ta .= "<li>$r[label] harus diisi dengan kode berikut:<br/>";
				$temparr = array();
				unset($r['value']['']);
				foreach ($r['value'] as $k => $v) {
					$temparr[] = "$k : $v";
				}
				$ta .= implode("<br/>", $temparr);
				$ta .= "</li>";
			}
			if ($r['type'] == 'listinverst') {
				$ta .= "<li>$r[label] harus diisi dengan kode berikut:<br/>";
				$temparr = array();
				unset($r['value']['']);
				foreach ($r['value'] as $k => $v) {
					$temparr[] = "- $v";
				}
				$ta .= implode("<br/>", $temparr);
				$ta .= "</li>";
			}
		}
		$ta .= '</li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>	
      </div>
    </div>
  </div>
</div>
		
		<button class="btn btn-export btn-link fileinput-button" type="button" onclick="window.location=\'' . site_url($page_ctrl . "/export_list" . $add) . '\'">
	        <i class="bi bi-download"></i>
		Download Templete
		</button>
		<span class="btn btn-import btn-link fileinput-button">
	        <i class="bi bi-upload"></i>
	        <span>Import Data</span>
	        <input id="' . $nameid1 . 'upload" name="' . $nameid1 . 'upload" type="file">
	    </span></div>';

		$ta .= "<script>
			$(function () {
			'use strict';
		    $('#" . $nameid1 . "upload').fileupload({
		        url: \"" . site_url($page_ctrl . "/import_list" . $add) . "\",
		        dataType: 'json',
		        done: function (e, data) {
		        	$('#" . $nameid1 . "errors').html('');
		            if(data.result.success){
	        			$('#btn" . $nameid1 . "').hide();
		            	window.location='';
			        }

		            if(data.result.error){
	        			$('#btn" . $nameid1 . "').show();
		            	var error = data.result.error;
		                $('<p onclick=\"$(this).remove()\">'+error+'</p>').appendTo('#" . $nameid1 . "errors');
			        }

		            $('#" . $nameid1 . "progress').hide();
		        },
		        progressall: function (e, data) {
		            $('#" . $nameid1 . "progress').show();
		            var progress = parseInt(data.loaded / data.total * 100, 10);
		            $('#" . $nameid1 . "progress .progress-bar').css(
		                'width',
		                progress + '%'
		            );
	        		$('#btn" . $nameid1 . "').hide();
		        },
		        fail: function(a, data){
		        	$('#" . $nameid1 . "errors').html('');
                	$('<p onclick=\"$(this).remove()\">'+data.errorThrown+'</p>').appendTo('#" . $nameid1 . "errors');
		            $('#" . $nameid1 . "progress').hide();
	        		$('#btn" . $nameid1 . "').show();
		        }
		    }).prop('disabled', !$.support.fileInput)
		        .parent().addClass($.support.fileInput ? undefined : 'disabled');
		});
		</script><div style='clear:both;margin-bottom:5px'></div>";

		return $ta;
	}

	public static function AddFormTable($id = null, $rows = array(), $form, $edited = false, $ci = array(), $acces_edit = null)
	{
		// dpr($edited, 1);
		if (empty($rows))
			$rows = array();

		$ci = get_instance();
		$ret = "";

		if (strstr($ci->post['act'], 'remove_' . $id) !== false) {
			$no = str_replace('remove_' . $id . '_', '', $ci->post['act']);
			unset($rows[$no]);
		}

		if ($ci->post['act'] == 'add_' . $id)
			$rows = array_merge($rows, array(array()));

		if (!is_array($rows)) {
			$rows = array($rows);
		}

		$tot = count($rows);

		$no = 1;

		// dpr($rows,1);
		foreach ($rows as $k => $val) {
			// $k = (int)$k;
			$ret .= "<tr>";

			if (empty($val))
				$val = null;

			$ret .= $form($val, $edited, $k, $ci, $no, $rows);
			if ($edited) {
				if (@$val['edit'] !== 'edit') {
					$ret .= "<button style='margin-right:0px; float:right;' type='submit' class='btn btn-danger btn-xs' onclick=\"goSubmit('remove_" . $id . "_" . $k . "','#main_form')\"> <i class='bi bi-trash'></i></button>";
					// if (in_array($ci->page_ctrl, array("panelbackend/risk_analisis", "panelbackend/risk_penanganan","panelbackend/risk_risiko")))
					// if (in_array($ci->page_ctrl, array("panelbackend/risk_analisis", "panelbackend/risk_penanganan")))
					// dpr($val['id']);
					if ($acces_edit && $val['id'])
						$ret .= "<button style='margin-right:0px; float:right;' type='submit' class='btn btn-warning btn-xs' onclick=\"goSubmit('edit_" . $id . "_" . $val['id'] . "','#main_form')\"> <i class='bi bi-pencil-square'></i></button>";
				} else if ($val['edit'] == 'edit') {
					$ret .= "<button style='margin-right:0px; float:right;' type='submit' class='btn btn-success btn-xs' onclick=\"goSubmit('save_" . $id . "_" . $val['id'] . "','#main_form')\"> <i class='bi bi-upload'></i></button>";
				}
			}
			$ret .= "</td></tr>";
			$no++;
		}

		if ($edited) {
			$ret .= "<tr><td colspan='10'><button style='margin-right:0px; float:right;' type='submit' class='btn btn-info btn-xs' onclick=\"goSubmit('add_" . $id . "','#main_form')\"> <i class='bi bi-plus'></i></button></td></tr>";
		}

		return $ret;
	}
	public static function AddFormTable_cuntom($id = null, $rows = array(), $form, $edited = false, $ci = array(), $colspan)
	{
		if (empty($rows))
			$rows = array();

		if (!$colspan)
			$colspan = 10;
		$ci = get_instance();
		$ret = "";

		if (strstr($ci->post['act'], 'remove_' . $id) !== false) {
			$no = str_replace('remove_' . $id . '_', '', $ci->post['act']);
			unset($rows[$no]);
		}

		if ($ci->post['act'] == 'add_' . $id)
			$rows = array_merge($rows, array(array()));


		$tot = count($rows);

		$no = 1;

		// dpr($rows,1);
		foreach ($rows as $k => $val) {
			$k = (int)$k;
			$ret .= "<tr>";

			if (empty($val))
				$val = null;

			$ret .= $form($val, $edited, $k, $ci);
			if ($edited) {
				$ret .= "<button type='submit' class='btn btn-danger btn-sm' onclick=\"goSubmit('remove_" . $id . "_" . $k . "','#main_form')\"> <i class='bi bi-trash'></i></button>";
			}
			$ret .= "</td></tr>";
		}

		if ($edited) {
			$ret .= "<tr><td colspan='$colspan'><button style='margin-right:5px; float:right;' type='submit' class='btn btn-info btn-sm' onclick=\"goSubmit('add_" . $id . "','#main_form')\"> <i class='bi bi-plus'></i></button></td></tr>";
		}

		return $ret;
	}
}
