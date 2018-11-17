<?php
require_once dirname ( __FILE__ ) . '/Define.php';

/**
 * Undocumented function
 * Gigazineのホームページから記事一覧を取得し、返す
 *
 * @return array
 */
function getTodayGigazine (): array
{
    // phpQueryの読み込み
    require_once dirname ( __FILE__ ) . '/phpQuery-onefile.php';

    // HTMLの取得
    $doc = phpQuery::newDocumentFile ( ARTICLE_ADDRESS );

    //必要な部分を大まかに取る
    $search = $doc['.content'];

    $dom = new DOMDocument();
    @$dom->loadHTML ( mb_convert_encoding ( $search, 'HTML-ENTITIES', 'UTF-8' ) );
    $xml = $dom->saveXML();
    return $res = json_decode ( json_encode ( simplexml_load_string ( $xml ) ), true );

}

/**
 * Undocumented function
 * 引数から該当の記事を取得して返す
 *
 * @param string $url
 * @return string
 */
function getOneArticle ( string $url ): string
{
    $ch = curl_init();
    $options = array (
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true, // curl_execの返り値をレスポンスボディにする

        CURLOPT_HTTPHEADER => array (
            // データの形式、文字コード記載
            'Content-Type: application/json; charser=UTF-8'
        ),

        CURLOPT_TCP_FASTOPEN => true, // TCPFastOpenでRTTを削減する (PHP7.0.7以降でのみ利用可能)
        CURLOPT_ENCODING => 'gzip', // 通信をgzipアルゴリズムで圧縮する

        CURLOPT_FAILONERROR => true, // 400以上のステータスコードはエラーと見なす (file_get_contentsはこれのtrue相当)
        CURLOPT_FOLLOWLOCATION => true, // Locationヘッダを追跡する (file_get_contentsはこれのtrue相当)
        CURLOPT_AUTOREFERER => true, // Locationヘッダ追跡時にRefererヘッダを自動で付加する
        CURLOPT_COOKIEFILE => '', // Cookieをメモリ上で自動管理させる
        CURLOPT_USERAGENT => USERAGENT, // UserAgentの指定
    );
    curl_setopt_array ( $ch, $options );

    $data = curl_exec ( $ch );

    curl_close ( $ch );
    return $data;
}
