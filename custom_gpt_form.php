<?php

/*
Plugin Name: custom_gpt_form
Plugin URI: 
Description: ファインチューニングしたGPTがFAQなどを答えてくれます。
Version: 1.0
Author: tabata atuyoshi
Author URI: http://cmslab.jp
*/

// エラー表示を有効にする
ini_set('display_errors', 1);
error_reporting(E_ALL);

$response_content = ''; // レスポンスを格納する変数

// フォームが送信されたときの処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['submit_form'])) {
    // 入力されたデータをサニタイズ
    $question = sanitize_text_field($_POST['question']);

    // 送信するデータの配列を作成
    $data = array('question' => $question);

    // cURLセッションを初期化
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.docsbot.ai/teams/〇〇',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 1,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
    ));

    // cURLリクエストを実行し、応答を変数に格納
    $response = curl_exec($curl);
    curl_close($curl);

    // レスポンスのデコード
    $decoded_response = json_decode($response, true);
    $response_content = htmlspecialchars($decoded_response['answer'], ENT_QUOTES, 'UTF-8');
}

// WordPressのフォームを表示する関数
function display_form() {
    global $response_content; // グローバル変数を使用

    // 出力をバッファリング
    ob_start();
    ?>
    <!-- フォームのHTML部分 -->
    <div id="custom-form-container">
        <form action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ) ?>" method="post">
            <input type="text" name="question" required>
            <input type="submit" name="submit_form" value="送信">
        </form>
        <!-- レスポンスを表示するコンテナ -->
        <div id="response-container" style="margin-top: 20px; border: 1px solid #ddd; padding: 10px; max-width: 600px;">
            <?php echo $response_content; // レスポンスを表示 ?>
        </div>
    </div>
    <?php
    // バッファの内容を取得して返す
    return ob_get_clean();
}

// WordPressのショートコードを追加
add_shortcode('custom_gpt_form_shortcode', 'display_form');
?>


*/