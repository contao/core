<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * Core translations are managed using Transifex. To create a new translation
 * or to help to maintain an existing one, please register at transifex.com.
 * 
 * @link http://help.transifex.com/intro/translating.html
 * @link https://www.transifex.com/projects/p/contao/language/ja/
 * 
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

$GLOBALS['TL_LANG']['tl_install']['installTool'][0] = 'インストール';
$GLOBALS['TL_LANG']['tl_install']['installTool'][1] = 'インストールツールにログイン';
$GLOBALS['TL_LANG']['tl_install']['locked'][0] = 'インストールツールはロックされています。';
$GLOBALS['TL_LANG']['tl_install']['locked'][1] = '連続して3回以上誤ったパスワードが入力された後、セキュリティ上の理由でインストールツールはロックされています。ロックを解除するには、サイトにローカルな構成ファイル(注: Contaoのディレクトリ以下のsystem/config/localconfig.php)を編集して、<em>installCount</em>を<em>0</em>にします。';
$GLOBALS['TL_LANG']['tl_install']['password'][0] = 'パスワード';
$GLOBALS['TL_LANG']['tl_install']['password'][1] = 'インストールツールのパスワードを入力してください。インストールツールのパスワードは、Contaoのバックエンドのパスワードと別のものです。';
$GLOBALS['TL_LANG']['tl_install']['changePass'][0] = 'インストールツールのパスワード';
$GLOBALS['TL_LANG']['tl_install']['changePass'][1] = 'Contaoのインストールツールをさらに安全にするには、<strong>contao/install.php</strong>のファイルの名前を変更するか、完全に削除しても良いでしょう。';
$GLOBALS['TL_LANG']['tl_install']['encryption'][0] = '暗号鍵の生成';
$GLOBALS['TL_LANG']['tl_install']['encryption'][1] = 'この鍵は暗号化してデータを保存するときに使用します。暗号鍵だけで暗号化したデータを復号できることに注意してください! 従って、どこかに書き留めておいて、暗号化したデータが既に存在する場合は変更してはなりません。空のままにすると、ランダムな鍵を生成します。';
$GLOBALS['TL_LANG']['tl_install']['database'][0] = 'データベースの接続確認';
$GLOBALS['TL_LANG']['tl_install']['database'][1] = 'データベースの接続の設定値を入力してください。';
$GLOBALS['TL_LANG']['tl_install']['collation'][0] = '照合順序';
$GLOBALS['TL_LANG']['tl_install']['collation'][1] = 'より詳しい情報は<a href="http://dev.mysql.com/doc/refman/5.1/en/charset-unicode-sets.html" target="_blank">MySQLマニュアル</a>を参照してください。';
$GLOBALS['TL_LANG']['tl_install']['update'][0] = 'データベースのテーブルを更新';
$GLOBALS['TL_LANG']['tl_install']['update'][1] = 'この更新の支援はMySQLとMySQLiのドライバーだけでテストしています。異なるデータベース(例えば、Oracle)を使用する場合は、手動でデータベースのインストールや更新が必要になるでしょう。';
$GLOBALS['TL_LANG']['tl_install']['template'][0] = 'テンプレートをインポート';
$GLOBALS['TL_LANG']['tl_install']['template'][1] = '<em>templates</em>のディレクトリから、予め構成済みのサンプルのウェブサイトの<em>.sql</em>のファイルをインポートできます。既存のデータを削除します! テーマをインポートしたいだけの場合は、代わりにContaoのバックエンドでテーマ管理を使用してください。';
$GLOBALS['TL_LANG']['tl_install']['admin'][0] = '管理者ユーザーの作成';
$GLOBALS['TL_LANG']['tl_install']['admin'][1] = 'ウェブサイトの例をインポートした場合は、管理者のユーザー名は<strong>k.jones</strong>、パスワードは<strong>kevinjones</strong>となります。より詳しい情報はインポートしたウェブサイト(のフロントエンド)を参照してください。';
$GLOBALS['TL_LANG']['tl_install']['completed'][0] = 'おめでとうございます!';
$GLOBALS['TL_LANG']['tl_install']['completed'][1] = 'すぐに<a href="contao/">Contaoのバックエンド</a>にログインして、システムの設定を確認してください。それからウェブサイトにアクセスしてContaoが正しく動作していることを確認してください。';
$GLOBALS['TL_LANG']['tl_install']['ftp'][0] = 'ファイルをFTP用いて修正';
$GLOBALS['TL_LANG']['tl_install']['ftp'][1] = 'ウェブサーバーはPHPによるファイルアクセスをサポートしていません。これはPHPがApacheのモジュールで異なるユーザーで動作していると考えられます。そこで、FTPのログイン情報を入力してください、ContaoがファイルをFTPを使用して修正できるようにします。(セーフモード対処)';
$GLOBALS['TL_LANG']['tl_install']['accept'] = '使用許諾の受け入れ';
$GLOBALS['TL_LANG']['tl_install']['beLogin'] = 'Contaoのバックエンドにログイン';
$GLOBALS['TL_LANG']['tl_install']['passError'] = '不正なアクセスを防止するため、パスワードを入力してください!';
$GLOBALS['TL_LANG']['tl_install']['passConfirm'] = '入力されたパスワードを設定しました。';
$GLOBALS['TL_LANG']['tl_install']['passSave'] = 'パスワードを保存';
$GLOBALS['TL_LANG']['tl_install']['keyError'] = '暗号鍵を作成してください。';
$GLOBALS['TL_LANG']['tl_install']['keyLength'] = '暗号鍵は少なくとも12文字の長さが必要です。';
$GLOBALS['TL_LANG']['tl_install']['keyConfirm'] = '暗号鍵を作成しました。';
$GLOBALS['TL_LANG']['tl_install']['keyCreate'] = '暗号鍵を生成';
$GLOBALS['TL_LANG']['tl_install']['keySave'] = '鍵を生成または保存';
$GLOBALS['TL_LANG']['tl_install']['dbConfirm'] = 'データベースの接続を確立しました。';
$GLOBALS['TL_LANG']['tl_install']['dbError'] = 'データベースに接続していません!';
$GLOBALS['TL_LANG']['tl_install']['dbDriver'] = 'ドライバー';
$GLOBALS['TL_LANG']['tl_install']['dbHost'] = 'ホスト';
$GLOBALS['TL_LANG']['tl_install']['dbUsername'] = 'ユーザー名';
$GLOBALS['TL_LANG']['tl_install']['dbDatabase'] = 'データベース名';
$GLOBALS['TL_LANG']['tl_install']['dbPersistent'] = '持続した接続';
$GLOBALS['TL_LANG']['tl_install']['dbCharset'] = '文字セット';
$GLOBALS['TL_LANG']['tl_install']['dbCollation'] = '照合順序';
$GLOBALS['TL_LANG']['tl_install']['dbPort'] = 'ポート番号';
$GLOBALS['TL_LANG']['tl_install']['dbSave'] = 'データベースの設定を保存';
$GLOBALS['TL_LANG']['tl_install']['collationInfo'] = '照合順序の変更は、すべての<em>tl_</em>から始まるテーブルに影響します。';
$GLOBALS['TL_LANG']['tl_install']['updateError'] = 'データベースが最新ではありません。';
$GLOBALS['TL_LANG']['tl_install']['updateConfirm'] = 'データベースは最新です。';
$GLOBALS['TL_LANG']['tl_install']['updateSave'] = 'データベースを更新';
$GLOBALS['TL_LANG']['tl_install']['saveCollation'] = '照合順序の変更';
$GLOBALS['TL_LANG']['tl_install']['updateX'] = 'バージョン%sより前のContaoからアップグレードのようです。その場合、データの完全性を確実にするために<strong>バージョン%sアップデートを実行する必要</strong>があります。';
$GLOBALS['TL_LANG']['tl_install']['updateXrun'] = 'バージョン%sアップデートを実行';
$GLOBALS['TL_LANG']['tl_install']['updateXrunStep'] = 'バージョン%sアップデート - ステップ%s';
$GLOBALS['TL_LANG']['tl_install']['importException'] = 'インポートに失敗しました!  データベースの構造は最新にしていて、テンプレートはContaoのバージョンと互換性がありますか?';
$GLOBALS['TL_LANG']['tl_install']['importError'] = 'テンプレートのファイルを選択してください。';
$GLOBALS['TL_LANG']['tl_install']['importConfirm'] = '%sにテンプレートをインポートしました。';
$GLOBALS['TL_LANG']['tl_install']['importWarn'] = '現在のデータをすべて削除します。';
$GLOBALS['TL_LANG']['tl_install']['templates'] = 'テンプレート';
$GLOBALS['TL_LANG']['tl_install']['doNotTruncate'] = 'テーブルを切り捨てない';
$GLOBALS['TL_LANG']['tl_install']['importSave'] = 'テンプレートをインポート';
$GLOBALS['TL_LANG']['tl_install']['importContinue'] = '現在のデータをすべて削除します。本当に続けますか?';
$GLOBALS['TL_LANG']['tl_install']['adminError'] = 'すべての欄に入力して管理者ユーザーを作成してください。';
$GLOBALS['TL_LANG']['tl_install']['adminConfirm'] = '管理者ユーザーを作成しました。';
$GLOBALS['TL_LANG']['tl_install']['adminSave'] = '管理者アカウントを作成';
$GLOBALS['TL_LANG']['tl_install']['installConfirm'] = 'Contaoのインストールに成功しました。';
$GLOBALS['TL_LANG']['tl_install']['ftpHost'] = 'FTPのホスト名';
$GLOBALS['TL_LANG']['tl_install']['ftpPath'] = 'Contaoのディレクトリの相対パス(例: <em>httpdocs/</em>)';
$GLOBALS['TL_LANG']['tl_install']['ftpUser'] = 'FTPのユーザー名';
$GLOBALS['TL_LANG']['tl_install']['ftpPass'] = 'FTPのパスワード';
$GLOBALS['TL_LANG']['tl_install']['ftpSSLh4'] = '安全な接続';
$GLOBALS['TL_LANG']['tl_install']['ftpSSL'] = 'FTP-SSLで接続';
$GLOBALS['TL_LANG']['tl_install']['ftpPort'] = 'FTPのポート';
$GLOBALS['TL_LANG']['tl_install']['ftpSave'] = 'FTPの設定を保存';
$GLOBALS['TL_LANG']['tl_install']['ftpHostError'] = '%sのFTPサーバーに接続できません。';
$GLOBALS['TL_LANG']['tl_install']['ftpUserError'] = '"%s"というユーザーでログインできません。';
$GLOBALS['TL_LANG']['tl_install']['ftpPathError'] = '%sというContaoのディレクトリに移動できません。';
$GLOBALS['TL_LANG']['tl_install']['filesRenamed'] = '設定したファイルディレクトリが存在しません。';
$GLOBALS['TL_LANG']['tl_install']['filesWarning'] = 'バージョン3アップデートの前に<em>tl_files</em>から<em>files</em>に名前を変更していませんか? 単にフォルダーの名前を変更はできません、というのもデータベースはすべてのファイルを参照していて、いまだにスタイルシートは以前の場所を指しているからです。フォルダーの名前を変更したい場合は、バージョン3アップデートの後で行い、データベースのデータを以下のスクリプトで調整してください。';
$GLOBALS['TL_LANG']['tl_install']['CREATE'] = '新しいテーブルを作成';
$GLOBALS['TL_LANG']['tl_install']['ALTER_ADD'] = '新しい列を追加';
$GLOBALS['TL_LANG']['tl_install']['ALTER_CHANGE'] = '既存の列を変更';
$GLOBALS['TL_LANG']['tl_install']['ALTER_DROP'] = '既存の列を削除';
$GLOBALS['TL_LANG']['tl_install']['DROP'] = '既存のテーブルを削除';
