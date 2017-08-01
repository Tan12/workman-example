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
          background-color: lightblue;
        }
        .show-box .username{
          background-color: white;
          text-align: center;
        }
        .show-box div[id^='show-msg']{
          width: 600px;
          height: 400px;
          overflow-y: scroll;
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
    <p class="username">当前用户：用户<span id="usernum">1</span></p>
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
      $showBox = $('.show-box'),
      //$showMsg1 = $('#show-msg-1'),
      $chatBox = $('#chat-box'),
      $msg = $('#msg'),
      $sm = $('#sm'),
      $message = {},
      //height = $showMsg1.height(),
      cur_users = [], // 当前与客服聊天的所有用户id
      i = 0; // 开启服务后与客服聊天的用户人数，用户登出后不减1，会出现重复数字

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
      $message.to = $userList.val();  
      //console.log(JSON.stringify($message));
      console.log($message);
      ws.send(JSON.stringify($message));
      $p = $('<p>').text($msg.val());
      var tag = userNum($message.to);
      //console.log(tag);
      $('#show-msg-' + tag).append($p);
    }
    // 使滚动条保持在底部，即显示最新消息
    /*if(heightChange()){
      $(window).scrollTop(height);
    }*/
  return false;
  }); 

  ws.onmessage = function(msg){
    //console.log(msg);
    var $data = msg.data,
        $user2, $p,  $options, $div,
        user_id; // 用户唯一id

    // 判断是不是json数据，不是的话就是欢迎语
    if($data.indexOf('{') !== -1){
      $obj = $.parseJSON($data);
      console.log($obj)
      if($obj.type === 'user'){
        $p = $('<p>').text($obj.type + '对你说： ' + $obj.msg);

        // 如果是新用户则添加到用户列表，且新建一个聊天框
        if(!in_array(cur_users, $obj.from)){
          cur_users.push($obj.from);
          // 添加到用户列表
          $options = $('<option>').val($obj.from).text('用户' + ++i);
          $userList.append($options).val($obj.from);
          // 新建聊天框
          var id_name = 'show-msg-' + i;
          $div  = $('<div>').attr('id', 'show-msg-' + i).css('background-color', randColor());
          $div.addClass('current-user').append($p);
          $showBox.append($div);
          $div.siblings('div[id^=show-msg]').removeClass('current-user');
          $('#usernum').text(i);
        }else{
          // 如果是已经在聊天的用户则找到对应聊天框添加对话
          tag = userNum($obj.from);
          //console.log(tag);
          $('#show-msg-' + tag).append($p);
        }
      }else if($obj.type === 'logout'){ // 有用户退出，只有type跟from有值
        cur_users.splice($.inArray($obj.from, cur_users),1);
        console.log(cur_users);
        var x = 0, 
            $ops = $userList.children('option'),
            l = $ops.length;
        // 从用户列表中移除退出的用户
        for(x; x < l; x++){
          if($ops.eq(x).val() === $obj.from){
            $ops.eq(x).remove();
          }
        }
      }
      /*if($obj.userlist){
        console.log($obj.userlist);
        //$userList.empty();
        for(user_id in $obj.userlist){
          if(!in_array(cur_users, user_id)){
            cur_users.push(user_id);
            $options = $('<option>').val(user_id).text('用户' + ++i);
            $userList.append($options);
          }
        }
      }
      */
    }else{
      console.log($data);
    }
  };

  // 返回uid对应的是用户几
  function userNum(uid){
    var x = 0, 
        $ops = $userList.children('option'),
        l = $ops.length,
        tag; // 用户n
    for(x; x < l; x++){
      if($ops.eq(x).val() === uid){
        var u = $ops.eq(x).text();
        tag = u.substr(u.length-1, 1);
      }
    }
    return tag;
  }

  // 切换聊天用户
  $userList.change(function(){
    var index = $(this).get(0).selectedIndex + 1;
    console.log(index);
    // 将选中的用户聊天框置顶，其他聊天框移去置顶class样式
    $('#show-msg-' + index).addClass('current-user').siblings('div[id^=show-msg]').removeClass('current-user');
    $('#usernum').text(index);
  });

  // 判断show-msg高度是否发生改变
  /*function heightChange(){
      if($showMsg1.height() > height){
          height = $showMsg1.height();
          return 1;
      }
      return 0;
  }*/

  // 判断数组arr是否包含元素item
  function in_array(arr, item){
    for(var i = 0, l = arr.length; i < l; i++){
      if(arr[i] === item){
        return 1;
      }
    }
    return 0;
  }

  // 随机颜色
  function randColor(){
    var r=Math.floor(Math.random()*256);
    var g=Math.floor(Math.random()*256);
    var b=Math.floor(Math.random()*256);
    return "rgb("+r+','+g+','+b+")";//所有方法的拼接都可以用ES6新特性`其他字符串{$变量名}`替换
  }
});
</script>
</body>
</html>