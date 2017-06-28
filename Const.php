<?php
class Const
{
	const URL = 'я-книга.рф';
	const PARTNER_ID = 299612548;
	const POST_DESCRIPTION =
		'<img src="http://'. self::URL .'/upload/%s.jpg" alt="%s" width="228" height="368" class="aligncenter size-full" />
        <strong>Автор: </strong>%s
        <strong>Название: </strong>%s
        <strong>Жанр: </strong>%s
        <strong>Язык книги: </strong>Русский
        <strong>Формат: </strong>FB2, ePub, pdf, txt и другие
		%s
		<a class="button green" href="%s">Скачать!</a>';

    const POST_DOWNLOAD = 
    '<div id="litres_trials">
            Скачать книгу в форматах:&nbsp;
            <a href="http://www.litres.ru/gettrial/?art=%s&format=fb2.zip&lfrom=' . self::PARTNER_ID . '" class="a-litres">FB2</a>
            <a href="http://www.litres.ru/gettrial/?art=%s&format=epub&lfrom='    . self::PARTNER_ID . '" class="a-litres">ePUB</a>
            <a href="http://www.litres.ru/gettrial/?art=%s&format=a4.pdf&lfrom='  . self::PARTNER_ID . '" class="a-litres">PDF</a>
            <a href="http://www.litres.ru/gettrial/?art=%s&format=txt.zip&lfrom=' . self::PARTNER_ID . '" class="a-litres">TXT</a>
        </div>'
}