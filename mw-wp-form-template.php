<?php
/**
 * Plugin Name: MW WP Form My Template
 * Description: MW WP Form関連のフック処理を集めて初期テンプレートにしてみたオレオレアドオン
 * Version: 1.0
 * Author: Kei Nomura
 */

class Mypace_MW_WP_Form_Config{

/* ------------------------------------------------ 
		フック指定
------------------------------------------------ */

	public function __construct() {
		add_filter( 'mwform_default_title',    array( $this, 'my_default_title' ), 10, 3 );
		add_filter( 'mwform_default_content',  array( $this, 'my_default_content' ), 10, 3 );
		add_filter( 'mwform_default_settings', array( $this, 'my_default_settings' ), 10, 3 );
	}

/* ------------------------------------------------ 
		初期値指定
------------------------------------------------ */

	//フォームタイトルの初期値を設定
	public function my_default_title( $title ){
		$title = 'お問い合わせフォーム';
		return $title;
	}

	//フォーム本文の初期値を設定
	public function my_default_content( $content ){
		$content = '<div class="h-adr">
<script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
<input type="hidden" class="p-country-name" value="Japan" />
<table width="100%">
<tbody>
<tr>
<th scope="row">お名前 <span class="req">※必須</span></th>
<td>[mwform_text name="name01"]</td>
</tr>
<tr>
<th scope="row">ふりがな <span class="req">※必須</span></th>
<td>[mwform_text name="kana01"]</td>
</tr>
<tr>
<th scope="row">TEL <span class="req">※必須</span></th>
<td>[mwform_text name="tel01"]</td>
</tr>
<tr>
<th scope="row">E-mail <span class="req">※必須</span></th>
<td>[mwform_text name="mail01"]</td>
</tr>
<tr>
<th scope="row">御社所在地 <span class="req">※必須</span></th>
<td><p>郵便番号 [mwform_text name="zip01" class="p-postal-code" maxlength="8"]</p>
<p>番地まで [mwform_text name="addr01" class="p-region p-locality p-street-address p-extended-address"]</p>
<p>ビル名等 [mwform_text name="addr02"]</p></td>
</tr>
<tr>
<th scope="row">お問い合わせ内容 <span class="req">※必須</span></th>
<td>[mwform_textarea name="txt01"]</td>
</tr>
</tbody>
</table>
<p class="addBtn"><span class="back">[mwform_backButton value="入力内容を変更する"]</span><span class="submit">[mwform_submitButton name="submit" confirm_value="確認画面へ進む" submit_value="この内容で送信する"]</span></p>
</div>';
		return $content;
	}

	//フォーム設定項目の初期値を設定
	public function my_default_settings( $option, $key ){
		switch( $key ) {
			/* ---- 完了画面メッセージ ---- */
			case 'complete_message':
				$option = '
<h2>お問い合わせ送信完了</h2>
<p>お問い合わせありがとうございました。<br />
担当者が内容を確認の上、改めてご連絡いたします。<br />
今しばらくお待ちください。</p>
<p>&raquo;<a href="/contact">お問い合わせフォームへ戻る</a></p>
				';
				break;

			/* ---- URL設定 ---- */
			case 'input_url':
				$option = '/contact'; //入力画面URL
				break;
			case 'confirmation_url':
				$option = '/contact/confirm'; //確認画面URL
				break;
			case 'complete_url':
				$option = '/contact/complete'; //完了画面URL
				break;
			case 'validation_error_url':
				$option = '/contact/error'; //エラー画面URL
				break;

			/* ---- お客様向け 自動返信メール設定 ---- */
			case 'mail_subject' :
				$option = 'お問い合わせありがとうございます'; //件名
				break;
			case 'mail_content' :
				$option = "＊＊＊＊＊＊株式会社へのお問い合わせ、ありがとうございます。
本メールはメールフォームより送信した内容をお知らせする自動返信メールです。
送信された内容は下記の通りですのでお確かめください。

【お名前】{name01}
【ふりがな】{kana01}
【TEL】{tel01}
【E-mail】{mail01}
【ご住所】〒{zip01}
  {addr01} {addr02}
【お問い合わせ内容】
  {txt01}

担当者が折り返しご連絡いたしますので、今しばらくお待ちください。

=======================================================
 会社名
 〒460-0000 住所
 TEL：  FAX： 
 URL: http://
=======================================================";
				break;
			case 'automatic_reply_email' :
				$option = 'mail01'; //自動返信メールのキー名
				break;

			/* ---- 管理者向けメール設定 ---- */
			case 'admin_mail_sender' :
				$option = '{name01}'; //送信者名
				break;
			case 'admin_mail_from' :
				$option = '{mail01}'; //送信元アドレス
				break;
			case 'admin_mail_subject' :
				$option = 'Webサイトからお問い合わせがありました'; //件名
				break;
			case 'admin_mail_content' :
				$option = "以下の内容でお問い合わせがありました。

【お名前】{name01}
【ふりがな】{kana01}
【TEL】{tel01}
【E-mail】{mail01}
【ご住所】〒{zip01}
  {addr01} {addr02}
【お問い合わせ内容】
  {txt01}";
				break;

			/* ---- バリデーション設定項目 ---- */
			case 'validation' :
				$option = array(
					array(
						'target'  => 'name01',
						'noempty' => true,
					),
					array(
						'target'  => 'kana01',
						'noempty' => true,
						'kana'    => true,
					),
					array(
						'target'  => 'mail01',
						'noempty' => true,
						'mail'    => true,
					),
					array(
						'target'  => 'tel01',
						'noempty' => true,
						'tel'    => true,
					),
					array(
						'target'  => 'zip01',
						'zip'    => true,
					),
					array(
						'target'  => 'addr01',
						'noempty' => true,
					),
					array(
						'target'  => 'txt01',
						'noempty' => true,
					),
				);
				break;
		}
		return $option;
	}

}
new Mypace_MW_WP_Form_Config();
