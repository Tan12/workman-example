<!DOCTYPE html>
<html>
<head>
    <title>客户端</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <style>
        .container{
          width: 80%;
          margin: 0 auto;
          margin-top: 20px;
        }
        #show-msg{
          width: 400px;
          height: 300px;
          overflow-y: scroll;
          background-color: lightblue;
          margin: 30px 0;
        }
    </style>
</head>
<body>
<p>请选择客服：</p>
<select id="server-list">
  <option value="001">客服1</option>
  <option value="002">客服2</option>
</select>
<div class="container">
    <div id="show-msg"></div>
    <form id="chat-box">
        <input id="msg" type="text" name="message" />
        <input id="sm" type="submit" name="submit" value="提交" />
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script>
$(function(){
  ws = new WebSocket("ws://"+document.domain+":2333");
  ws.onopen = function() {
      console.log("连接成功");
  };
  var $showMsg = $('#show-msg'),
      $chatBox = $('#chat-box'),
      $msg = $('#msg'),
      $sm = $('#sm'),
      $serverList = $('#server-list'),
      $message = {},
      height = $showMsg.height();

  $message.type = 'user'; // 发消息的人，默认是客服
  $message.msg = ''; // 消息内容
  $message.to = ''; // 这个消息是发给谁的
  $message.from = ''; // 这个消息是来自谁的
  $message.time = '';

  // 表单提交
  $chatBox.submit(function(){
    if(!$msg.val()){
      alert('please input something.');
    }else{
      $message.to = $serverList.val();
      $message.msg = $msg.val();   
      //console.log(JSON.stringify($message));
      console.log($message);
      ws.send(JSON.stringify($message));
      $p = $('<p>').text($msg.val());
      $showMsg.append($p);
    }
    // 使滚动条保持在底部，即显示最新消息
    if(heightChange()){
      $(window).scrollTop(height);
    }
    return false;
  }); 
  ws.onmessage = function(msg){
    //console.log(msg);
    var $data = msg.data,
        $user2, $p;

    // 判断是不是json数据，不是的话就是欢迎语
    if($data.indexOf('{') !== -1){
      $obj = $.parseJSON($data);
      console.log($obj)
      if($obj.type !== 'ping'){
        $p = $('<p>').text($obj.type + '对你说： ' + $obj.msg);
        $message.to = $obj.from;
        $message.from = $obj.to;
        $showMsg.append($p);
      }
    }else{
      console.log($data);
    }
  };

    // 生成唯一的用户id号
    function creatUid(len, radix) {
        var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.split('');
        var uid = [],
            i = 0;
        radix = radix || chars.length;

        if(len){
            for (i; i < len; i++){
              uid[i] = chars[0 | Math.random() * radix];
            }
        }else{
            var r;
            uid[8] = uid[13] = uid[18] = uid[23] = '-';
            uid[14] = '4';
            for (i; i < 36; i++) {
                if (!uid[i]) {
                    r = 0 | Math.random() * 16;
                    uid[i] = chars[(i == 19) ? (r & 0x3) | 0x8 : r];
                }
            }
        }
        return uid.join('');
    }

  // 判断show-msg高度是否发生改变
  function heightChange(){
      if($showMsg.height() > height){
          height = $showMsg.height();
          return 1;
      }
      return 0;
  }
});
</script>
</body>
</html>