        /*
        旧方式タイマー
        setTimeoutを使った方式
        */
        //制御フラグ
        var timer_flag = 0;
        //時間初期設定
        var min = 3;
        var sec = 0;
        //起動時にタイマーを初期化する
        window.onload = function () {
            var timer = document.getElementById("text");
            timer.innerHTML = "0" + min + ":" + sec;
            //タイマースイッチ類起動
            set_timer();
        }

        //タイマーのスイッチ類
        function set_timer () {
            //スタート
            var timer_start = document.getElementById("start");
            timer_start.addEventListener("click", move_timer, false);
            //ストップ
            var timer_stop = document.getElementById("stop");
            timer_stop.addEventListener("click", stop_timer, false);
            //リセット
            var timer_reset = document.getElementById("reset");
            timer_reset.addEventListener("click", reset_timer, false);
        }

        //タイマーストップ
        function stop_timer () {
            //スタートを有効化
            document.getElementById("start").disabled = false;
            //フラグ設定
            timer_flag = 1;
            return;
        }

        //タイマーリセット
        function reset_timer () {
            //スタートを有効化
            document.getElementById("start").disabled = false;
            //タイマーリセット
            min = 3;
            sec = 0;
            //タイマー表示初期化
            var reset_time = document.getElementById("text");
            if (sec < 10) {
                sec = "0" + sec;
            }
            reset_time.innerHTML = "0" + min + ":" + sec;
            //フラグ設定
            timer_flag = 1;
            return;
        }

        //タイマースタート
        function move_timer () {
            //スタートボタン無効化
            document.getElementById("start").disabled = true;
            //flagが有効なら停止
            if (timer_flag == 1) {
                clearTimeout(move_timer);
                document.getElementById("start").disabled = false;
                timer_flag = 0;
                return;
            }
            //強引なタイマー実装（後で直す） => 新タイマーに移行
            if (min == 3 && sec == 0) {
                min = 2;
                sec = 60;
            }else if(min == 2 && sec == 0) {
                min = 1;
                sec = 60;
            }else if (min == 1 && sec == 0) {
                min = 0;
                sec = 60;
            }
            sec--;
            //終了時処理
            if (min == 0 && sec == 0) {
                timer_flag = 0;
                clearTimeout(move_timer);
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
            //１秒ごとにmove_timer関数を動かす
            if (timer_flag == 0){
                timer_flag = 0;//この宣言は多分いらない
                setTimeout(move_timer, 1000);
            }
        }

