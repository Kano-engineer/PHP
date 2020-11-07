<?php

 // メッセージを保存するファイルのパス設定
 define( 'FILENAME', './message.txt');

 // タイムゾーン設定 cf.世界標準時
 date_default_timezone_set('Asia/Tokyo');

 //  変数を空の値で宣言=初期化 cf.理由
 //  null=zero or nothing
 $now_date = null;
 $data = null;
 $file_handle = null;
 $split_data = null;
 $message = array();
 $message_array = array();

 // cf.情報を出力する変数
 if( !empty($_POST['btn_submit']) ) {
 // forpen(パス、モード)
 // a=ファイルを書き込みモードで開く。ファイルをリセットせずに追記。
 // cf.ファイルポインターリソース(ファイルへのアクセス情報)
 if( $file_handle = fopen( FILENAME, "a") ) {

		$now_date = date("Y-m-d H:i:s");
	
 // 分割元のデータ [0-6]
		$data = "'".$_POST['view_name']."','".$_POST['message']."','".$now_date."'\n";

		fwrite( $file_handle, $data);
	
 //  取得したファイルポインターリソースを渡して閉じるファイルを特定。
		fclose( $file_handle);
	}
}

 // while文=指定した条件式が真(true)の間,繰り返し実行(ループ)
 // fileから一行読み取る,文字列 = fgets(ハンドル)
 if( $file_handle = fopen( FILENAME,'r') ) {
    while( $data = fgets($file_handle) ){

 // Q.'/\'/'の役割。'/\//'や'/bb/'だと打ち込んだ文字が表示されず、日付も1970年01月01日09:00と表示される理由
        $split_data = preg_split( '/\'/', $data);
 // 読み込まれたデータを配列に分割。
 // array(7) {
 // 	[0]=> string(0) ""
 // 	[1]=> string(5) "タイトル"
 // 	[2]=> string(1) ","
 // 	[3]=> string(33) "記事"
 // 	[4]=> string(1) ","
 // 	[5]=> string(19) "年月日時刻"
 // 	[6]=> string(1) " " ＝改行
 // }
        $message = array(
            'view_name' => $split_data[1],
            'message' => $split_data[3],
            'post_date' => $split_data[5]
        );
        array_unshift( $message_array, $message);
		}
		
 // ファイルを閉じる
    fclose( $file_handle);
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>Laravel News</title>

</head>
<body>
<h1>Laravel News</h1>
	<h2>さあ、最新のニュースをシェアしましょう</h2>

<!-- ここにメッセージの入力フォームを設置 -->
<form method="post">
	<div>
		<label for="view_name">タイトル：</label>
		<input id="view_name" type="text" name="view_name" value="">
	</div>
	<div>
		<label for="message">記事：</label>
		<textarea id="message" name="message"></textarea>
	</div>
	<input type="submit" name="btn_submit" value="書き込む">
</form>

<hr>
<section>
<!-- ここに投稿されたメッセージを表示 -->
<?php if( !empty($message_array) ): ?>
<?php foreach( $message_array as $value ): ?>
<article>
    <div class="info">
        <h2><?php echo $value['view_name']; ?></h2>
        <time><?php echo date('Y年m月d日 H:i', strtotime($value['post_date'])); ?></time>
    </div>
    <p><?php echo $value['message']; ?></p>
</article>
<?php endforeach; ?>
<?php endif; ?>
</section>
</body>
</html>