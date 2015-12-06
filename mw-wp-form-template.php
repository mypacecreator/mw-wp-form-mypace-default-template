<?php
/**
 * Plugin Name: MW WP Form Template
 * Description: MW WP Form関連のフック処理を集めたもの
 * Version: 2.6.3
 * Author: Kei Nomura
 */

class Mypace_MW_WP_Form_Config{

/* ------------------------------------------------ 
		フック指定
------------------------------------------------ */

	public function __construct() {
		add_filter( 'mwform_default_title',    array( $this, 'my_default_title' ), 10 );
		add_filter( 'mwform_default_content',  array( $this, 'my_default_content' ), 10 );
		add_filter( 'mwform_default_settings', array( $this, 'my_default_settings' ), 10 );


	//mw-wp-form-xxxx のxxxx部分にフックを適用するフォームのID値を指定
		add_filter( 'mwform_post_content_mw-wp-form-4177',    array( $this, 'my_mail_content' ), 10 );
		add_filter( 'mwform_admin_mail_raw_mw-wp-form-4177',  array( $this, 'my_admin_mail' ), 10 );
		add_filter( 'mwform_auto_mail_raw_mw-wp-form-4177',   array( $this, 'my_user_mail' ), 10 );
		add_filter( 'mwform_validation_mw-wp-form-4177',      array( $this, 'my_validation_rule' ), 10 );

	}

/* ------------------------------------------------ 
		初期値指定
------------------------------------------------ */

	//フォームタイトルの初期値を設定
	public function my_default_title( $title ){
		$title = 'フックで出すタイトル';
		return $title;
	}

	//フォーム本文の初期値を設定
	public function my_default_content( $content ){
		$content = '<p>これはフックで出力したもの</p>
<div class="h-adr">
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
<h3>これはフックの完了メッセージ</h3>
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
		
		//return var_dump($key);
		return $option;
	}


/* ------------------------------------------------ 
		個別のフォーム内容定義
------------------------------------------------ */

	//個別フォームの内容を設定
	public function my_mail_content( $content ){
		$content = '
<p>これはフックで出力したもの</p>
<div class="h-adr">
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
</div>
		';
		return $content;
	}


	//管理者向け
	function my_admin_mail( $Mail_raw, $values, $Data ) {
			$Browser = $_SERVER["HTTP_USER_AGENT"];
			$Ip = $_SERVER["REMOTE_ADDR"];
			$Host = gethostbyaddr($Ip);
			$org_timezone = date_default_timezone_get();
			date_default_timezone_set('Asia/Tokyo');
			$Datetime = date("Y年n月j日 H:i:s");
			date_default_timezone_set($org_timezone);

			// to, cc, bcc では {キー} は使用できません。
			// $Data->get( 'hoge' ) で送信されたデータが取得できます。
			$Mail_raw->to      = 'k@ozn.pw'; // 送信先を変更
			$Mail_raw->from    = $Data->get('mail01'); // 送信元を変更
			$Mail_raw->sender  = $Data->get('name01'); // 送信者を変更
			$Mail_raw->subject = '管理者向けメール'; // 件名を変更
			$Mail_raw->body    = "Webサイトからお問い合わせがありました。
【お名前】{name01}
【ふりがな】{kana01}
【TEL】{tel01}
【E-mail】{mail01}
【ご住所】〒{zip01}
  {addr01} {addr02}
【お問い合わせ内容】
{txt01}

--------------------
【送信者情報】
・ブラウザー：$Browser
・送信元IPアドレス：$Ip
・送信元ホスト名：$Host
・送信日時：$Datetime";

		return $Mail_raw;
	}

	//お客様向け自動返信メール設定
	function my_user_mail( $Mail_raw, $values, $Data ) {
			// to, cc, bcc では {キー} は使用できません。
			// $Data->get( 'hoge' ) で送信されたデータが取得できます。
			$Mail_raw->from    = 'hoge@example.jp'; // 送信元を変更
			$Mail_raw->sender  = '管理者名'; // 送信者を変更
			$Mail_raw->subject = 'ユーザー向けメール'; // 件名を変更
			$Mail_raw->body    = "お問い合わせありがとうございます。

			【お名前】{name01}
			【ふりがな】{kana01}
			【TEL】{tel01}
			【E-mail】{mail01}
			【ご住所】〒{zip01}
			{addr01} {addr02}
			【お問い合わせ内容】
			{txt01}

=======================================================
	会社名
	〒460-0000 住所
	TEL：  FAX： 
	URL: http://
=======================================================";

		return $Mail_raw;
	}


	/* バリデーションルール
		$Validation->set_rule( 'キー名', 'バリデーション項目', 'array( 'message' => 'エラーメッセージの内容 ※オプション' )' );
		指定できる項目↓
		http://plugins.2inc.org/mw-wp-form/manual/auto_mail/
	*/
	public function my_validation_rule( $Validation, $data, $Data ){
			$Validation->set_rule( 'name01', 'noEmpty' );
			$Validation->set_rule( 'kana01', 'noEmpty' );
			$Validation->set_rule( 'kana01', 'kana' );
			$Validation->set_rule( 'tel01',  'noEmpty' );
			$Validation->set_rule( 'tel01',  'tel' );
			$Validation->set_rule( 'mail01', 'noEmpty' );
			$Validation->set_rule( 'mail01', 'mail' );
			$Validation->set_rule( 'zip01',  'zip' );
			$Validation->set_rule( 'txt01',  'noEmpty' );
		return $Validation;
	}


}
new Mypace_MW_WP_Form_Config();