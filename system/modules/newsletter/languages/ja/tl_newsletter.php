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

$GLOBALS['TL_LANG']['tl_newsletter']['subject'][0] = '件名';
$GLOBALS['TL_LANG']['tl_newsletter']['subject'][1] = 'ニュースレターの件名(Subject)を入力してください。';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][0] = 'ニュースレターのエイリアス';
$GLOBALS['TL_LANG']['tl_newsletter']['alias'][1] = 'ニュースレターのエイリアスは、その数値のIDの代わりにニュースレターを参照できる、重複しない名前です。';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][0] = 'HTMLの内容';
$GLOBALS['TL_LANG']['tl_newsletter']['content'][1] = 'ニュースレターのHTMLによる内容を入力します。<em>##email##</em>というワイルドカードを使用して、宛先の電子メールアドレスを挿入できます。';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][0] = 'テキストの本文';
$GLOBALS['TL_LANG']['tl_newsletter']['text'][1] = 'ニュースレターのテキストによる内容を入力します。<em>##email##</em>というワイルドカードを使用して、宛先の電子メールアドレスを挿入できます。';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][0] = '添付ファイルを追加';
$GLOBALS['TL_LANG']['tl_newsletter']['addFile'][1] = 'ファイルを1つ以上ニュースレターに添付します。';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][0] = '添付ファイル';
$GLOBALS['TL_LANG']['tl_newsletter']['files'][1] = 'ファイルディレクトリから添付するファイルを選択してください。';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][0] = '電子メールのテンプレート';
$GLOBALS['TL_LANG']['tl_newsletter']['template'][1] = '電子メールのテンプレートを選択します。';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][0] = 'テキスト形式で送信';
$GLOBALS['TL_LANG']['tl_newsletter']['sendText'][1] = 'ニュースレターをHTMLのパートを含めないでテキスト形式の電子メールとして送信します。';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][0] = '外部の画像';
$GLOBALS['TL_LANG']['tl_newsletter']['externalImages'][1] = 'HTMLのニュースレターに画像のファイルを添付しません。';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][0] = '送信者の名前';
$GLOBALS['TL_LANG']['tl_newsletter']['senderName'][1] = '送信者の名前を入力できます。';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][0] = '送信者のアドレス';
$GLOBALS['TL_LANG']['tl_newsletter']['sender'][1] = '特定の送信者のアドレスを入力できます。';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][0] = '一度に送信するメールの数';
$GLOBALS['TL_LANG']['tl_newsletter']['mailsPerCycle'][1] = 'スクリプトのタイムアウトを避けるため、送信の処理を複数に分けて行います。';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][0] = '送信を待つ秒数';
$GLOBALS['TL_LANG']['tl_newsletter']['timeout'][1] = '「一度に送信するメールの数」のメールを送った後に待つ時間を変更して、1分毎のメールを送信する数を調整できます。';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][0] = '補正値';
$GLOBALS['TL_LANG']['tl_newsletter']['start'][1] = '送信の処理が中断した場合に、特定の宛先から継続する補正値を入力できます。何通が送信されたかは<em>system/logs/newsletter_*.log</em>のファイルで確認できます。例えば、120通のメールが送信済みの場合は、121番目の宛先から送信を続けるために(0から数えた)120と入力します。';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][0] = 'プレビューの宛先';
$GLOBALS['TL_LANG']['tl_newsletter']['sendPreviewTo'][1] = 'ニュースレターのプレビューをこの電子メールアドレスに送信します。';
$GLOBALS['TL_LANG']['tl_newsletter']['title_legend'] = '題名と件名';
$GLOBALS['TL_LANG']['tl_newsletter']['html_legend'] = 'HTMLの内容';
$GLOBALS['TL_LANG']['tl_newsletter']['text_legend'] = 'テキストの内容';
$GLOBALS['TL_LANG']['tl_newsletter']['attachment_legend'] = '添付ファイル';
$GLOBALS['TL_LANG']['tl_newsletter']['template_legend'] = 'テンプレートの設定';
$GLOBALS['TL_LANG']['tl_newsletter']['expert_legend'] = '専門的な設定';
$GLOBALS['TL_LANG']['tl_newsletter']['sent'] = '送信状態';
$GLOBALS['TL_LANG']['tl_newsletter']['sentOn'] = '%sに送信済み';
$GLOBALS['TL_LANG']['tl_newsletter']['notSent'] = '未送信';
$GLOBALS['TL_LANG']['tl_newsletter']['mailingDate'] = 'メールした日';
$GLOBALS['TL_LANG']['tl_newsletter']['confirm'] = 'ニュースレターを%sつの宛先に送信しました。';
$GLOBALS['TL_LANG']['tl_newsletter']['rejected'] = '%sつの正しくない電子メールアドレスの宛先を無効にしました。(システムログを参照してください。)';
$GLOBALS['TL_LANG']['tl_newsletter']['error'] = 'このチャンネルに有効な登録者がいません。';
$GLOBALS['TL_LANG']['tl_newsletter']['from'] = '発信元';
$GLOBALS['TL_LANG']['tl_newsletter']['attachments'] = '添付ファイル';
$GLOBALS['TL_LANG']['tl_newsletter']['preview'] = 'プレビューを送信';
$GLOBALS['TL_LANG']['tl_newsletter']['sendConfirm'] = 'ニュースレターを本当に送信済みしますか?';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][0] = '新しいニュースレター';
$GLOBALS['TL_LANG']['tl_newsletter']['new'][1] = '新しくニュースレターを作成';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][0] = 'ニュースレターの詳細';
$GLOBALS['TL_LANG']['tl_newsletter']['show'][1] = 'ID %sのニュースレターの詳細を表示';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][0] = 'ニュースレターを編集';
$GLOBALS['TL_LANG']['tl_newsletter']['edit'][1] = 'ID %sのニュースレターを編集';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][0] = 'ニュースレターを複製';
$GLOBALS['TL_LANG']['tl_newsletter']['copy'][1] = 'ID %sのニュースレターを複製';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][0] = 'ニュースレターを移動';
$GLOBALS['TL_LANG']['tl_newsletter']['cut'][1] = 'ID %sのニュースレターを移動';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][0] = 'ニュースレターを削除';
$GLOBALS['TL_LANG']['tl_newsletter']['delete'][1] = 'ID %sのニュースレターを削除';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][0] = 'チャンネルを変更';
$GLOBALS['TL_LANG']['tl_newsletter']['editheader'][1] = 'このチャンネルの設定を変更';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][0] = 'このチャンネルに貼り付け';
$GLOBALS['TL_LANG']['tl_newsletter']['pasteafter'][1] = 'ID %sのニュースレターの後に貼り付け';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][0] = 'ニュースレターを送信';
$GLOBALS['TL_LANG']['tl_newsletter']['send'][1] = 'ID %sのニュースレターを送信';
