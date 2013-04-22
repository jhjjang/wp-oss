<input type="hidden" name="_ncn" value="product">
<table width="90%" border=1>
	<tr>
		<td>제품명</td>
		<td><input type="text" name="post_title" value="<?=$prd_nm?>"></td>
		<td>요약설명</td>
		<td><input type="text" name="prd_short_desc" value="<?=$prd_short_desc?>"></td>
	</tr>
	<tr>
		<td>Description</td>
		<td colspan="3"><?php wp_editor("$content", 'content'); ?></td>
	</tr>
	<tr>
		<td>OS</td>
		<td><input type="text" name="os" value="<?=$os?>"></td>
		<td>License</td>
		<td><input type="text" name="prd_license" value="<?=$prd_license?>"></td>
	</tr>
	<tr>
		<td>제공업체</td>
		<td><input type="text" name="apply_company" value="<?=$apply_company?>"></td>
		<td>기술지원업체</td>
		<td><input type="text" name="tech_company" value="<?=$tech_company?>"></td>
	</tr>
	<tr>
		<td>Project URL</td>
		<td><input type="text" name="project_url" value="<?=$project_url?>"></td>
		<td>추천수</td>
		<td><input type="text" name="recom_num" value="<?=$recom_num?>"></td>
	</tr>
	<tr>
		<td>추천점수</td>
		<td><input type="text" name="recom_point" value="<?=$recom_point?>"></td>
		<td>다운로드 URL</td>
		<td><input type="text" name="file_url" value="<?=$file_url?>"></td>
	</tr>
</table>