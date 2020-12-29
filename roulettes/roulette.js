//各種変数を定義
//ルーレットフラグ(リセットでの強制停止用)
var roulette_flag = false;
//インターバルタイマー用変数
var roulette_id;
//初期配列→for文で生成する案も頂きましたがこちらで提出します
var rest_id = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
//ルーレットのスピード
var time = 50;
//初期ルーレット数
var num = 16;
//テンポラリ変数（リーダブルコードを買って読みます...）
var buff = null;
var swap = null;
var atv = null;

//起動時。set_button()を起動
$(function(){
    //ストップボタンを無効化
    $('#stop').prop('disabled', true);
    //ボタン類を起動
    set_button();
});


//ボタン制御関数
function set_button() {
    //スタート
    $('#start').click(start_roulette);
    //ストップ
    $('#stop').click(stop_roulette);
    //リセット
    $('#reset').click(reset_roulette);
}

//ルーレットストップ
function stop_roulette() {
    //ルーレットフラグ設定
    roulette_flag = false;
    //スタート・リセットボタンの有効化
    $('#start,#reset').prop('disabled', false);
    //ストップボタンに無効化
    $('#stop').prop('disabled', true);
    //インターバル解除
    clearInterval(roulette_id);
    //止まったマス目を紫に変更
    $('#main_block div').eq(rest_id[buff]).addClass('activate');
    //numを1つ減らす
    num--;
    //使った数字は配列の後ろへ押し込む→numは1つ減るのでその番号には当たらない
    //参照：https://qiita.com/yambejp/items/56494b19d46b354f02d5
    //atvは.activate専用の変数。なので先に設定する
    atv = rest_id[buff];
    swap = rest_id[num];
    rest_id[num] = rest_id[buff];
    rest_id[buff] = swap;
    //numが無くなったらスタートボタンを無効化
    if (num === 0) {
        $('#start').prop('disabled', true);
    }
}

//ルーレットスタート
function start_roulette() {
        //インターバル動作開始
        roulette_id = setInterval(move_roulette, time);
        //.activateを削除する
        $('#main_block div').removeClass('activate')
        //同時に.confirmを付加(ただし、numが15以下の時のみ)
        if (num <= 15) {
        $('#main_block div').eq(atv).addClass('confirm');
        }
}

function reset_roulette() {
    //roulette_flagがtrueの時、強制停止
    if (roulette_flag === true) {
        clearInterval(roulette_id);
        //ルーレットフラグの無効化
        roulette_flag = false;
    }
    //付いた色(=class)を消す
    $('#main_block div').removeClass('confirm').removeClass('selected').removeClass('activate');
    //スタート・リセットボタンを有効化
    $('#start,#reset').prop('disabled', false);
    //各種変数リセット
    //ここをもっときれいにしたい→このままにすることでfix
    rest_id = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
    num = 16;
    buff = null;
    swap = null;
    atv = null;
    return;
}

//ルーレット動作関数
function move_roulette() {
    //ルーレットフラグ有効化
    roulette_flag = true;
    //スタート、リセットボタンを無効化
    $('#start').prop('disabled', true);
    //ストップボタン有効化
    $('#stop').prop('disabled', false);
    //ランダムで数字を選ぶ
    buff = Math.floor(Math.random() * num);
    //1マスに色を消す & 1マス色をつける
    $('#main_block div').removeClass('selected').eq(rest_id[buff]).addClass('selected');
}



