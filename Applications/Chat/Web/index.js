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
      var $section = $('<section>'),
          $p = $('<p>').text($msg.val()).addClass('right');
      $section.append($p),
      $showMsg.append($section);
    }
    if(heightChange()){
      $showMsg.scrollTop(height);
    }
    return false;
  }); 
  ws.onmessage = function(msg){
    //console.log(msg);
    var $data = msg.data,
        $user2, $section, $p,
        $obj;

    // 判断是不是json数据，不是的话就是欢迎语
    if($data.indexOf('{') !== -1){
      $obj = $.parseJSON($data);
      //console.log($obj)
      if($obj.type === 'server'){
        $section = $('<section>');
        $p = $('<p>').text($obj.msg).addClass('left');
        $message.to = $obj.from;
        $message.from = $obj.to;
        $section.append($p);
        $showMsg.append($section);
        // 使滚动条保持在底部，即显示最新消息
        if(heightChange()){
          $showMsg.scrollTop(height);
        }
      }
    }else{
      console.log($data);
    }
  };

  // 判断show-msg高度是否发生改变
  function heightChange(){
    if($showMsg[0].scrollHeight > height){
      height = $showMsg[0].scrollHeight;
      console.log(height);
      return 1;
    }
    return 0;
  }
});