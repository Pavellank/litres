<?php

	//собираем массив автозамен из общего удаленного файла
	/*$csv_handle = fopen('http://www.litres.ru/static/ds/sangalov/litres_replacements.csv','rb');
	while (($data = fgetcsv($csv_handle, 1000, ";")) !== false) {
		$data[0] = mb_convert_encoding($data[0],'utf8','cp1251');
		$data[1] = mb_convert_encoding($data[1],'utf8','cp1251');
		$data[2] = mb_convert_encoding($data[2],'utf8','cp1251');
		if ($data[0] == 'автор'){
			$repl_auth_ar[$data[1]] = $data[2];
		}
		else{
			$repl_ar[$data[1]] = $data[2];
		}
		unset($data);
	}
	fclose($csv_handle);*/
	
	$repl_auth_ar['Микаловиц'] = 'Михаловиц';
	
	$repl_ar['(сборник)'] = '';
	$repl_ar['(Сборник)'] = '';
	$repl_ar['Учредитель и его фирма: все вопросы. От создания до ликвидации'] = 'Учредитель и его фирма';
	$repl_ar['Мы пойдем другим путем! От «капитализма Юрского периода» к России будущего'] = 'Мы пойдем другим путём';
	$repl_ar['Планирование продаж и операций: Практическое руководство'] = 'Планирование продаж и операций';
	$repl_ar['Структурирование хаоса или практическое руководство по управлению командой'] = 'Структурирование хаоса';
	$repl_ar['Школа руководителя, бизнесмена и менеджера. Бизнес в России – руководство по технике безопасности'] = 'Бизнес в России';
	$repl_ar['Как составить личный финансовый план и как его реализовать'] = 'Как составить личный финансовый план';
	$repl_ar['Будущее глазами одного из самых влиятельных инвесторов в мире. Почему Азия станет доминировать, у России есть хорошие шансы, а Европа и Америка продолжат падение'] = 'Будущее';
	$repl_ar['Наживемся на кризисе капитализма… или Куда правильно вложить деньги'] = 'Наживемся на кризисе капитализма';
	$repl_ar['Как превратить буквы в деньги? Что такое копирайтинг?'] = 'Как превратить буквы в деньги';
	$repl_ar['Сможет ли Россия конкурировать? История инноваций в царской, советской и современной России'] = 'Сможет ли Россия конкурировать';
	$repl_ar['Психология убеждения. 50 доказанных способов быть убедительным'] = 'Психология убеждения';
	$repl_ar['Выход из кризиса и альтернатива коррупции, разрухе и нефтяной игле'] = 'Выход из кризиса';

	//собираем массив адресных замен из общего удаленного файла
	$csv_handle = fopen('http://www.litres.ru/static/ds/sangalov/litres_sync.csv','rb');
	$j = 0;
	while (($data = fgetcsv($csv_handle, 1000, ";", '"')) !== false) {
		$data[0] = mb_convert_encoding($data[0],'utf8','cp1251');
		$data[1] = mb_convert_encoding($data[1],'utf8','cp1251');
		$sync_array[$j][0] = $data[0];
		$sync_array[$j][1] = $data[1];
		$sync_array[$j][2] = $data[2];
		$sync_array[$j][3] = $data[3];
		unset($data);
		$j++;
	}
	fclose($csv_handle);
	
?>