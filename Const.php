<?php
class Const
{
	const URL = 'я-книга.рф';
	const PARTNER_ID = 299612548;
	const POST_DESCRIPTION =
		'<img src="http://'. Const::URL .'/upload/%s.jpg" alt="%s" width="228" height="368" class="aligncenter size-full" />
        <strong>Автор: </strong>%s
        <strong>Название: </strong>%s
        <strong>Жанр: </strong>%s
        <strong>Язык книги: </strong>Русский
        <strong>Формат: </strong>FB2, ePub, pdf, txt и другие
		%s
		<a class="button green" href="%s">Скачать!</a>'; 
}