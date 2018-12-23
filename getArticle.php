<?php
require_once dirname ( __FILE__ ) . '/Define.php';
require_once dirname ( __FILE__ ) . '/Functions.php';

$data = '';
$saveDir = dirname ( __FILE__ ) . ARTICLE_DIR;
$arg = $argv[1];

$article = explode ( ",", $arg );

if ( empty ( $article[0] ) ) return;

$url = $article[0];
$tags = empty ( $article[1] ) ? NOCLASS : $article[1];
$title = empty ( $article[2] ) ? NOTITLE : str_replace('/', '&&', $article[2]);

$data = getOneArticle ( $url );

//ディレクトリがなければ生成
if ( !file_exists ( $saveDir . $tags ) )
{
    mkdir ( $saveDir . $tags, 0777, true );
}

file_put_contents ( $saveDir . $tags . '/' . $title . EXTENSION, $data );
