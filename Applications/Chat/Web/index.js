$(function(){
  var ws,
      $select = $('.select');
  $('#click-me').click(function(){
    $select.fadeIn(300);

    ws = new WebSocket("ws://"+document.domain+":2333");
    ws.onopen = open;
    ws.onmessage = getMessage;
  });  

  function open(){
    ws.send('userlink');
    console.log("连接成功");
  }
  var $showMsg = $('#show-msg'),
      $chatBox = $('#chat-box'),
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
    var $serverList = $('#server-list');
    if(!$msg.val()){
      alert('请输入内容~');
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
      $msg.val('');
    }
    if(heightChange()){
      $showMsg.scrollTop(height);
    }
    return false;
  }); 
  function getMessage(data){
    console.log(data);
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
      }else if($obj.type === 'online'){ // $obj.online包含了在线客服的号码，not the only id

        console.log($obj.online);
        if($obj.online){
          var $sel = $('<select>').attr('id', 'server-list'),
              $option,
              $p = $('<p>').text('请选择客服：'),
              $btn = $('<button>').attr('id', 'begin-chat').text('开始聊天');
          $select.children('select').remove();
          for(var i = 0, n = $obj.online.length; i < n; i++){
            $option = $('<option>').val($obj.online[i]).text('客服'+ $obj.online[i]);
            $sel.append($option);
          }
          $select.empty().append($p).append($sel).append($btn);

          $btn.on('click', function(){
            $showMsg.empty();
            $('.chat-container').fadeIn(300);
            $('#s_num').text($('#server-list').val());
            $('.ask-me').hide();
            $select.hide();
          })
        }else{
          var $p = $('<p>').text('当前没有客服在线，请稍后再试哦~')
          $select.empty().append($p);
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
      console.log(height);
      return 1;
    }
    return 0;
  }
});