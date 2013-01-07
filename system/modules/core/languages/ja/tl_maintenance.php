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

$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][0] = 'データの消去';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheTables'][1] = '消去や再作成したいデータを選択してください。';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][0] = 'フロントエンドのユーザー';
$GLOBALS['TL_LANG']['tl_maintenance']['frontendUser'][1] = '保護されたページのインデックスを作成するため、フロントエンドのユーザーに自動的にログインします。';
$GLOBALS['TL_LANG']['tl_maintenance']['job'] = '作業';
$GLOBALS['TL_LANG']['tl_maintenance']['description'] = '説明';
$GLOBALS['TL_LANG']['tl_maintenance']['clearCache'] = 'データを消去';
$GLOBALS['TL_LANG']['tl_maintenance']['cacheCleared'] = 'データを消去しました。';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdate'] = 'ライブアップデート';
$GLOBALS['TL_LANG']['tl_maintenance']['liveUpdateId'] = 'ライブアップデートのID';
$GLOBALS['TL_LANG']['tl_maintenance']['toLiveUpdate'] = 'ライブアップデートに移動';
$GLOBALS['TL_LANG']['tl_maintenance']['upToDate'] = '現在のContaoバージョン%sは最新です。';
$GLOBALS['TL_LANG']['tl_maintenance']['newVersion'] = '新しいContaoバージョン%sを利用できます。';
$GLOBALS['TL_LANG']['tl_maintenance']['emptyLuId'] = 'ライブアップデートのIDを入力してください。';
$GLOBALS['TL_LANG']['tl_maintenance']['notWriteable'] = '一時フォルダー(system/tmp)に書き込みできません。';
$GLOBALS['TL_LANG']['tl_maintenance']['changelog'] = '変更履歴を表示';
$GLOBALS['TL_LANG']['tl_maintenance']['runLiveUpdate'] = 'アップデートを実行';
$GLOBALS['TL_LANG']['tl_maintenance']['toc'] = 'アップデートのアーカイブの内容';
$GLOBALS['TL_LANG']['tl_maintenance']['backup'] = 'バックアップしたファイル';
$GLOBALS['TL_LANG']['tl_maintenance']['update'] = '更新したファイル';
$GLOBALS['TL_LANG']['tl_maintenance']['searchIndex'] = '検索インデックスの再構築';
$GLOBALS['TL_LANG']['tl_maintenance']['indexSubmit'] = 'インデックスを再構築';
$GLOBALS['TL_LANG']['tl_maintenance']['noSearchable'] = '検索可能なページがありませんでした。';
$GLOBALS['TL_LANG']['tl_maintenance']['indexNote'] = '次に進まずに、ページが完全に読み込まれるまでお待ちください。';
$GLOBALS['TL_LANG']['tl_maintenance']['indexLoading'] = '検索インデックスを再構築中です、しばらくお待ちください。';
$GLOBALS['TL_LANG']['tl_maintenance']['indexComplete'] = '検索インデックスを再構築しました。次に進んでください。';
$GLOBALS['TL_LANG']['tl_maintenance']['updateHelp'] = '%sを入力してください。';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][0] = '検索のインデックスを消去';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['index'][1] = '<em>tl_seach</em> と <em>tl_search_index</em> のテーブルを切り捨てます。その後、検索のインデックスを再構築しなければなりません(上を参照)。';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][0] = '復元のテーブルを消去';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['undo'][1] = '<em>tl_undo</em> のテーブルを切り捨てます。この作業は恒久的にこれらのレコードを削除します。';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][0] = 'バージョンを消去';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['versions'][1] = 'レコードの以前のバージョンを保持している、 <em>tl_version</em> のテーブルを切り捨てます。この作業は恒久的にこれらのレコードを削除します。';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][0] = '画像キャッシュの消去';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['images'][1] = '自動的に生成した画像とページのキャッシュを削除し、削除したリソースへのリンクはなくなります。';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][0] = 'スクリプトのキャッシュを消去';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['scripts'][1] = '自動的に生成した拡張子が .css と .js のファイルを削除し、内部のスタイルシートを再作成して、ページキャッシュを消去します。';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][0] = 'ページキャッシュを消去';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['pages'][1] = 'フロントエンドのページをキャッシュ版を削除します。';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][0] = '内部キャッシュを消去';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['internal'][1] = 'DCAと言語ファイルのキャッシュ版を削除します。バックエンドの設定で内部キャッシュを恒久的に無効にできます。';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][0] = '一時フォルダーを消去';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['temp'][1] = '一時ファイルを削除します。';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][0] = 'XMLファイルを再作成';
$GLOBALS['TL_LANG']['tl_maintenance_jobs']['xml'][1] = 'XMLファイル(サイトマップとフィード)を再作成してページキャッシュを消去し、削除したリソースへのリンクはなくなります。';
