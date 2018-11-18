<?php
require_once dirname ( __FILE__ ) . '/Define.php';
require_once dirname ( __FILE__ ) . '/Functions.php';

//クローリングする
$res = getTodayGigazine ();

//スクレイピング Start

//記事一覧の部分だけ配列化
$article = $res['body']['div'][0]['section'];
foreach ( $article as $section )
{
    $articleURL = empty ( $section['div']['div'][0]['a']['@attributes']['href'] ) ? '' : $section['div']['div'][0]['a']['@attributes']['href'];
    $category = empty ( $section['div']['div'][1]['a']['span'] ) ? '' : $section['div']['div'][1]['a']['span'];
    $title = empty ( $section['div']['h2']['a']['span'] ) ? '' : $section['div']['h2']['a']['span'];

    if ( empty( $articleURL ) || $category == '広告' ) continue;//広告とURLなしの項目が混じっていたため、省く
    $articleArray[] = array ( $articleURL, $category, $title );//記事を配列に格納
}

//スクレイピング結果をまとめるファイルを指定
$log = dirname ( __FILE__ ) . ARTICLE_DIR . ARTICLE_LOG;//今まで取得した投稿のパラメータを保存するファイル
$newArticle = dirname ( __FILE__ ) . ARTICLE_DIR . DOWNLOAD;//クロールしたとき、ダウンロードした投稿のパラメータを保存するファイル

//今まで取得したURLの一覧のファイルがなければ生成
if ( !file_exists ( $log ) )
{
    touch ( $log );
}
//今回取得したURL一覧のファイルがなければ生成
if ( !file_exists ( $newArticle ) )
{
    touch ( $newArticle );
}

//今回取得したURL一覧のファイルをオープン
$na = fopen ( $newArticle, 'w' );
foreach ( $articleArray as $k => $v )
{
    //今まで取得したURLの一覧をオープン
    $l = fopen ( $log, 'r+' );

    while ( !feof ( $l ) )
    {
        //一行読み込む
        $tmp = fgets ( $l );
        //取得した一行から改行を除く
        $tmp = str_replace ( PHP_EOL, '', $tmp );
        //一行をコンマ区切りで配列にする
        $tmp = explode ( ',', $tmp );
        //URLがすでに取得したものだったら処理を終了させる
        if ( $tmp[0] == $v[0] ) break;
        //最後の行まで読んでもなかった場合
        if ( feof ( $l ) )
        {
            //「URL、カテゴリ名、タイトル」の順で文字列化する。
            $newData = implode ( ',', $v );
            //今まで取得したURL一覧に記載
            fwrite ( $l, $newData . "\n" );
            //今回取得したURL一覧に記載
            fwrite ( $na, $newData . "\n" );
        }
    }
    //処理が終わったら、ファイルを閉じる
    fclose ( $l );
}
//処理が終わったら、ファイルを閉じる
fclose ( $na );
//スクレイピング End
