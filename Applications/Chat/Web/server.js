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
      var to = $userList.children("input[type='radio']:checked").val();
      $message.to = to ? to : '';
      //console.log(JSON.stringify($message));
      console.log($message);
      ws.send(JSON.stringify($message));
      var $section = $('<section>'),
          $p = $('<p>').text($msg.val()).addClass('right'),
          tag = userNum($message.to);
      //console.log(tag);
      $section.append($p);
      $('#show-msg-' + tag).append($section);
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
        $user2,
        $obj,
        user_id; // 用户唯一id

    // 判断是不是json数据，不是的话就是欢迎语
    if($data.indexOf('{') !== -1){
      $obj = $.parseJSON($data);
      console.log($obj)
      if($obj.type === 'user'){
        var $section = $('<section>'),
            $p = $('<p>').text($obj.msg).addClass('left');
        $section.append($p);

        // 如果是新用户则添加到用户列表，且新建一个聊天框
        if(!in_array(cur_users, $obj.from)){
          cur_users.push($obj.from);
          i++;
          // 添加到用户列表
          var $label = $('<label>').attr('for', 'user'+i).text('用户' + i),
              $radio = $('<input>').attr({'type':'radio','id':'user'+i,'value':$obj.from,'name':'user',"checked":true});
          $userList.append($radio).append($label);
          $label.addClass('current').siblings('label').removeClass('current');
          $radio.addClass('hidden').siblings('input:radio').attr('checked', false);

          // 切换聊天用户
          // 绑定点击事件，当前函数之外绑定无效
          // 绑定已定义函数点击事件只能生效一次，so~
          $radio.on('click', function(){ // 切换聊天用户
            $(this).attr('checked', true).siblings('input:radio').attr('checked', false);
            $(this).siblings('label').removeClass('current');
            $(this).next('label').addClass('current')
            var index = $(this).attr('id').substr($(this).attr('id').length-1,1);
            //console.log(index);
            // 将选中的用户聊天框置顶，其他聊天框移去置顶class样式
            $('#show-msg-' + index).addClass('current-user').siblings('div[id^=show-msg]').removeClass('current-user');
          });

          // 新建聊天框
          var id_name = 'show-msg-' + i;
          var $div  = $('<div>').attr('id', 'show-msg-' + i);
          $div.addClass('current-user').append($section);
          $showBox.append($div);
          $div.siblings('div[id^=show-msg]').removeClass('current-user');
          $('#usernum').text(i);
        }else{
          // 如果是已经在聊天的用户则找到对应聊天框添加对话
          tag = userNum($obj.from);
          //console.log(tag);
          $('#show-msg-' + tag).append($section);
        }
      }else if($obj.type === 'logout'){ // 有用户退出，只有type跟from有值
        cur_users.splice($.inArray($obj.from, cur_users),1);
        console.log(cur_users);
        var x = 0, 
            $list = $userList.children('input[type="radio"]'),
            l = $list.length;
        // 从用户列表中移除退出的用户
        for(x; x < l; x++){
          if($list.eq(x).val() === $obj.from){
            $list.eq(x).next('label').remove();
            $list.eq(x).remove();
          }
        }
      }
    }else{
      console.log($data);
    }
  };

  // 返回uid对应的是用户几
  function userNum(uid){
    var x = 0, 
        $list = $userList.children('input[type="radio"]'),
        l = $list.length,
        tag; // 用户n
    for(x; x < l; x++){
      if($list.eq(x).val() === uid){
        var u = $list.eq(x).attr('id');
        tag = u.substr(u.length-1, 1);
      }
    }
    return tag;
  }

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