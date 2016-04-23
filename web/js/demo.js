
wx.ready(function () {
  document.querySelector('#checkDeviceApi').onclick = function () {
    WeixinJSBridge.invoke('configWXDeviceWiFi', {}, function(res){
      if( res.err_msg == "configWXDeviceWiFi:ok")
      {
        alert("配置成功！");
        wx.closeWindow();
      }
      else
      {
        alert("配置失败！请重试!");
      }
    });
  };
});

