<?php
/// Модуль поиска новостей в RSS-фиде

    function searchStringInNews($stringSearch, $rssFeed) {
        // Ассоциативный массив с новостями
        $items = $rssFeed->getElementsByTagName('item');  // В это элементе хранятся подробности новости
        $count = $items->length;  // Получаем количество новостей для перебора

        for ($i = 0; $i < $count; $i++) {  // Перебор текста новостей

            $fullText = $items->item($i)->getElementsByTagName('full-text')->item(0)->textContent;
            $title = $items->item($i)->getElementsByTagName('title')->item(0)->textContent;
            $description = $items->item($i)->getElementsByTagName('description')->item(0)->textContent;

            if (strripos($fullText, $stringSearch)) {
                $letter = preg_replace("/$stringSearch/ui", "<b>$stringSearch</b>", $fullText);
                //$letter = str_replace($stringSearch, "<b>$stringSearch</b>", $letter);
                echo "Заголовок: $title<br/><br/>";
                echo "Описание: $description<br/><br/>";
                echo "Текст: $letter<br/><br/>";
                echo "<br/><br/>";
            }
        }
    }

    /// Эта функция получает контент фида и передаёт функции поиска XML-документ
    function getRssFromFeed() {
        $url = "http://static.feed.rbc.ru/rbc/logical/footer/news.rss";
        $doc = new DOMDocument();

        $opts = array(
            'http' => array(
                'user_agent' => 'PHP libxml agent',
            )
        );  // Флаги для того, чтобы пропускала защита сайта

        // Совершаем попытку взять данные с RSS фида
        try {
            $context = stream_context_create($opts);
            libxml_set_streams_context($context);

            $doc->load($url);  // Получаем XML-содержимое
        } catch (Exception $e) {
            echo $e->getMessage();
            die();
        }

        $stringSearch = $_POST['search'];
        searchStringInNews($stringSearch, $doc);
        // Передаём искомое слово и XML-документ, с которым нужно работать
    }

    /// Обработка существования фразы для поиска
    if (!empty($_POST['search'])) {
        getRssFromFeed();
    } else {
        echo "Ошибка поиска. Поле пустое";
    }
