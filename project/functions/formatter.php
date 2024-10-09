<?php

/**
 * Делает первую букву слова заглавной, если она не заглавная.
 *
 * @param string $word Слово для форматирования.
 * @return string Возвращает слово с первой заглавной буквой.
 */
function formatFirstLetter($word)
{
    $firstLetter = mb_substr($word, 0, 1, 'UTF-8');
    $endWord = mb_substr($word, 1, mb_strlen($word), 'UTF-8');
    return mb_strtoupper($firstLetter, 'UTF-8') . $endWord;
}

/**
 * Форматирует число, добавляя валюту (рубли).
 *
 * @param int|float $number Число для форматирования.
 * @return string Возвращает отформатированное число с добавленной валютой (рубли).
 */
function formatSum($word)
{
    return "$word руб.";
}
