
wx.ready(function () {
  document.querySelector('#checkDeviceApi').onclick = function () {
    WeixinJSBridge.invoke('configWXDeviceWiFi', {}, function(res){
      WeixinJSBridge.log(res.err_msg);
      WeixinJSBridge.log(res.desc);
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

