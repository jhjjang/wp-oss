<?
the_content();
?>

<table width="100%">
	<tr>
		<td>회사소개</td>
		<td><?php wp_editor("$content", 'content'); ?></td>
	</tr>
	<tr>
		<td>회사소개 다운로드</td>
		<td><input type="file" name="company_info_file"><?=$company_info_file?></td>
	</tr>
	<tr>
		<td>회사소개 이미지</td>
		<td><input type="file" name="company_img_file"><?=$company_img_file?></td>
	</tr>
	<tr>
		<td>Facebook URL</td>
		<td><input type="text" name="facebook_url" value="<?=$facebook_url?>"></td>
	</tr>
	<tr>
		<td>Twitter Account URL</td>
		<td><input type="text" name="twitter_url" value="<?=$twitter_url?>"></td>
	</tr>
</table>