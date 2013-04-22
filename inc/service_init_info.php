<?
$args=array();
$categories=get_categories($args);
?>

<table width="100%">
	<tr>
		<td>국적</td>
		<td>
			<input type="radio" name="nation" value="ko" <?=($nation=="ko") ? "checked":""?>>한국
			<input type="radio" name="nation" value="ch" <?=($nation=="ch") ? "checked":""?>>중국
			<input type="radio" name="nation" value="jp" <?=($nation=="jp") ? "checked":""?>>일본
		</td>
	</tr>
	<tr>
		<td>회사명</td>
		<td><input type="text" name="post_title" value="<? the_title()?>"></td>
	</tr>
	<tr>
		<td>기업구분</td>
		<td>
			<input type="checkbox" name="com_type[]" value="rnd" <?=(@in_array("rnd",$com_type)) ? "checked":""?>>R&D
			<input type="checkbox" name="com_type[]" value="tech" <?=(@in_array("tech",$com_type)) ? "checked":""?>>기술지원
			<input type="checkbox" name="com_type[]" value="usage" <?=(@in_array("usage",$com_type)) ? "checked":""?>>활용
			<input type="checkbox" name="com_type[]" value="consulting" <?=(@in_array("consulting",$com_type)) ? "checked":""?>>컨설팅
		</td>
	</tr>
</table>