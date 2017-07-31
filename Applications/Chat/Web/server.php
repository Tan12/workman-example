<!DOCTYPE html>
<html>
<head>
    <title>客服端</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <style>
        .container{
          width: 80%;
          margin: 0 auto;
          margin-top: 20px;
        }
        .show-box{
          width: 600px;
          height: 480px;
          margin: 30px 0;
          border: 1px solid #333;
        }
        .show-box .username{
          background-color: white;
          text-align: center;
        }
        .show-box div[id^='show-msg']{
          width: 600px;
          height: 400px;
          overflow-y: scroll;
          background-color: lightblue;
          position: absolute;
        }
        .current-user{
          z-index: 1024;
        }
    </style>
</head>
<body>
<div class="container">
  <p>你是？</p>
  <select id="server-list">
    <option value="001">客服1</option>
    <option value="002">客服2</option>
  </select>
  <p>跟你聊天的用户有：</p>
  <select id="user-list"></select>

  <div class="show-box">
    <p class="username">当前用户：用户<span>1</span></p>
    <div class="current-user" id="show-msg-1">www</div>
    <div id="show-msg-2">222</div>
  </div>
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
  var $userList = $('#user-list'),
      $showMsg1 = $('#show-msg-1'),
      $chatBox = $('#chat-box'),
      $msg = $('#msg'),
      $sm = $('#sm'),
      $message = {},
      height = $showMsg1.height(),
      to = '',
      from = '';
console.log(height);
  $message.type = 'server'; // 发消息的人
  $message.msg = ''; // 消息内容
  $message.to = ''; // 这个消息是发给谁的
  $message.from = ''; // 这个消息是来自谁的
  $message.time = '';
  $message.userlist = []; // 当前对话的用户id

  //ws.send(JSON.stringify($message));

  // 表单提交
  $chatBox.submit(function(){
    if(!$msg.val()){
      alert('please input something.');
    }else{
      $message.msg = $msg.val(); 
      $message.from = $('#server-list').val();  
      //console.log(JSON.stringify($message));
      console.log($message);
      ws.send(JSON.stringify($message));
      $p = $('<p>').text($msg.val());
      $showMsg1.append($p);
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
        $user2, $p, user_id, $options;

    // 判断是不是json数据，不是的话就是欢迎语
    if($data.indexOf('{') !== -1){
      $obj = $.parseJSON($data);
      console.log($obj)
      if($obj.type === 'user'){
        $p = $('<p>').text($obj.type + '对你说： ' + $obj.msg);
        $message.to = $obj.from;
        $message.from = $obj.to;
        $showMsg1.append($p);
      }
      if($obj.userlist){
        console.log($obj.userlist);
        var i = 0;
        $userList.empty();
        for(user_id in $obj.userlist){
          $options = $('<option>').val(user_id).text('用户' + ++i);
          $userList.append($options);
        }
      }
    }else{
      console.log($data);
    }
  };

  // 判断show-msg高度是否发生改变
  function heightChange(){
      if($showMsg1.height() > height){
          height = $showMsg1.height();
          return 1;
      }
      return 0;
  }
});
</script>
</body>
</html>