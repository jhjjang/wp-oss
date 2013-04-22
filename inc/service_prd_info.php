<SCRIPT type="text/javascript">
jQuery(function(){
	jQuery("#addBtn").click(function(){
		var html = "<li><br><input type='button' class='delBtn' value='삭제' class='button'><table border=0>";
		html += "<tr><td>제품명</td><td><input type='text' name='prd_nm[]' size=50></td></tr>";
		html += "<tr><td>제품소개</td><td><textarea name='prd_info[]' cols=50 rows=5></textarea></td></tr>";
		html += "<tr><td>제품URL</td><td><input type='text' name='prd_url[]' size=50></td></tr>";
		//html += "<tr><td>제품이미지</td><td><input type='file' name='prd_img[]'></td></tr>";
		html += "</table></li>";
		jQuery("#tbody").append(html);

	});

	jQuery(".delBtn").live('click',function(){
		jQuery(this).parent().remove();
	});

});
</SCRIPT>
<input type="button" id="addBtn" value="제품추가" class="button">
<table border=0 width="80%">
<tr>
	<td>
		<ul id="tbody">
			<?
			if(is_array($prd_nm_array)){
				foreach($prd_nm_array as $i=>$prd_nm){
					$prd_info = $prd_info_array[$i];
					$prd_url = $prd_url_array[$i];
					
					$prd_img_file_id    = get_post_meta($post->ID, 'prd_img_'.$i, true);
					if(!empty($prd_img_file_id) && $prd_img_file_id != '0') {
						$prd_img_file =  '<img src="' . wp_get_attachment_url($prd_img_file_id) . '" width=100></a>';
					}else{
						$prd_img_file_id = "";
						$prd_img_file = "";
					}
				?>
				<li><br><input type='button' class='delBtn' value='삭제' class='button'>
				<table border=0>
					<tr>
						<td>제품명</td>
						<td><input type="text" name="prd_nm[]" value="<?=$prd_nm?>" size="50"></td>
					</tr>
					<tr>
						<td>제품소개</td>
						<td><textarea name="prd_info[]" cols="50" rows="5"><?=$prd_info?></textarea></td>
					</tr>
					<tr>
						<td>제품URL</td>
						<td><input type="text" name="prd_url[]" value="<?=$prd_url?>" size="50"></td>
					</tr>
					<!--
					<tr>
						<td>제품 이미지</td>
						<td><input type="file" name="prd_img[]"><?=$prd_img_file?></td>
					</tr>
					-->
				</table>
				</li>
				<?}?>
			<?}else{?>
				<li>
				<table border=0>
					<tr>
						<td>제품명</td>
						<td><input type="text" name="prd_nm[]" size="50"></td>
					</tr>
					<tr>
						<td>제품소개</td>
						<td><input type="text" name="prd_info[]" size="80"></td>
					</tr>
					<tr>
						<td>제품URL</td>
						<td><input type="text" name="prd_url[]" size="50"></td>
					</tr>
					<!--
					<tr>
						<td>제품 이미지</td>
						<td><input type="file" name="prd_img[]"></td>
					</tr>
					-->
				</table>
				</li>
			<?}?>
		</div>
	</td>
</tr>
</table>