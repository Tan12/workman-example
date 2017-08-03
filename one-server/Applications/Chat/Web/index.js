$(function(){
  var ws = new WebSocket("ws://"+document.domain+":2333"),
      $showMsg = $('#show-msg');

  ws.onopen = open;
  ws.onmessage = getMessage;

  $('#click-me').click(function(){
    $('.chat-container').fadeIn(300);
    $('.ask-me').hide();
  });  

  function open(){
    ws.send('userlink');
    console.log("连接成功");
  }
  var $chatBox = $('#chat-box'),
      $msg = $('#msg'),
      $sm = $('#sm'),
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
      alert('请输入内容~');
    }else{
      $message.msg = $msg.val();
      //console.log($message);
      ws.send(JSON.stringify($message));
      var $section = $('<section>'),
          $p = $('<p>').text($msg.val()).addClass('right');
      $section.append($p),
      $showMsg.append($section);
      $msg.val('');
    }
    if(heightChange()){
      $showMsg.scrollTop(height);
    }
    return false;
  }); 

  function getMessage(data){
    //console.log(data);
    var $data = data.data,
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
      }else if($obj.type === 'online'){ // $obj.online包含了在线客服的号码
        if($obj.online){
          $message.to = $obj.online[0]; // 目前只有一个客服
        }else{
          alert('当前没有客服在线，请稍后再试哦~');
        }
      }
    }else{
      console.log($data);
    }
  };

  $('.close').on('click', function(){
    $('.chat-container').hide();
    $('.ask-me').fadeIn(300);
  });

  // 判断show-msg高度是否发生改变
  function heightChange(){
    if($showMsg[0].scrollHeight > height){
      height = $showMsg[0].scrollHeight;
      return 1;
    }
    return 0;
  }
});