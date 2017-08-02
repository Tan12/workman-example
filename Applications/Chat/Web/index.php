<!DOCTYPE html>
<html>
<head>
    <title>客户端</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <style>
      .select{
        width: 100px;
        margin: 0 auto;
      }
      .container{
        width: 520px;
        margin: 0 auto;
        margin-top: 20px;
        border: 1px solid #333;
      }
      #show-msg{
        width: 500px;
        height: 500px;
        margin: 10px;
        overflow-y: auto;
        overflow-x: hidden;
        background-color: white;
      }
      #show-msg section{
        clear: both;
      }
      #show-msg p{
        background-color: lightblue;
        max-width: 420px;
        padding: 5px;
        border-radius: 5px;
        display: inline-block;
        position:relative;
        vertical-align: top;
        word-wrap:break-word
      }
      #show-msg p.left{
        margin-left: 10px;
      }
      #show-msg p.left::before,
      #show-msg p.right::before{
        content: '';
        width: 0;
        height: 0;
        border: 8px solid transparent;
        position: absolute;
      }
      #show-msg p.left::before{
        border-right-color: lightblue;
        transform: translateX(-20px);
      }
      #show-msg p.right{
        margin-right: 10px;
        float: right;
      }
      #show-msg p.right::before{
        border-left-color: lightblue;
        right: -15px;
      }

      /*输入框*/
      #chat-box{
        text-align: center;
        margin: 10px 0;
      }
      #msg{
        width: 80%;
        border: 1px solid #44b549;
        padding: 8px 5px;
      }
      #sm{
        padding: 6px 20px;
        background-color: white;
        border: 1px solid #44b549;
      }
      #msg,
      #sm{
        border-radius: 5px;
      }
    </style>
</head>
<body>
<div class="select">
  <p>请选择客服：</p>
  <select id="server-list">
    <option value="001">客服1</option>
    <option value="002">客服2</option>
  </select>
</div>

<div class="container">
    <div id="show-msg"></div>
    <form id="chat-box">
        <input id="msg" type="text" name="message" />
        <input id="sm" type="submit" name="submit" value="提交" />
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="index.js"></script>
</body>
</html>