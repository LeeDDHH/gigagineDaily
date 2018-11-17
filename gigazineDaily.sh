#!/bin/zsh
#Common.phpのARTICLE_DIRとDOWNLOADをつなげ、ダウンロードした投稿のパラメータを保存するファイルが読み込めるように指定
#ex)../Documents/archive/サイト名/download.txt
download=""

php ./index.php
cat $download | while read line
do
  php ./getArticle.php $line
  sleep 60
done
echo "記事のカテゴライズが終わりました。"
