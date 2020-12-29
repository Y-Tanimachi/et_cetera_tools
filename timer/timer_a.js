        //制御フラグ
        var timer_id;
        var timer_flag = false;
        //時間初期設定
        var time = 180;
        var min = 0;
        var sec = 0;


        //起動時にタイマーを初期化する
        window.onload = function () {
            var timer = document.getElementById("text");
            timer.innerHTML = "0" + 3 + ":" + "0" + 0;
            //タイマースイッチ類起動
            set_timer();
        }

        //タイマーのスイッチ類
        function set_timer () {
            //スタート
            var timer_start = document.getElementById("start");
            timer_start.addEventListener("click", start_timer, false);
            //ストップ
            var timer_stop = document.getElementById("stop");
            timer_stop.addEventListener("click", stop_timer, false);
            //リセット
            var timer_reset = document.getElementById("reset");
            timer_reset.addEventListener("click", reset_timer, false);
        }
        //タイマースタート
        function start_timer () {
            if (timer_flag === false) {
            timer_id = setInterval(move_timer, 1000);
            timer_flag = true;
        }
        }


        //タイマーストップ
        function stop_timer () {
            //スタートを有効化
            document.getElementById("start").disabled = false;
            //タイマーストップ
            clearInterval(timer_id);
            timer_flag = false;
            return;
        }

        //タイマーリセット
        function reset_timer () {
            //スタートを有効化
            document.getElementById("start").disabled = false;
            //タイマーストップ
            clearInterval(timer_id);
            timer_flag = false;
            //タイマーリセット
            time = 180;
            min = 0;
            sec = 0;
            //タイマー表示初期化
            var reset_time = document.getElementById("text");
            reset_time.innerHTML = "0" + 3 + ":" + "0" + 0;
            return;
        }

        //タイマー動作メイン
        function move_timer () {
            //スタートボタン無効化
            document.getElementById("start").disabled = true;
            //新タイマー
            time--;
            min = Math.floor(time / 60);
            sec = Math.floor(time % 60);
            //終了時処理
            if (time === 0) {
                clearInterval(timer_id);
                var finish = document.getElementById("text");
                finish.innerHTML = "Time Up!";
                return;
            }
            //10秒以下の時の表示処理
            var move_time = document.getElementById("text");
            if (sec < 10) {
                sec = "0" + sec;
            }
            move_time.innerHTML = "0" + min + ":" + sec;
        }

