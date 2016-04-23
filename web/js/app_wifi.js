wx.ready(function () {  
  document.querySelector('#startWifi').onclick = function () {
    WeixinJSBridge.invoke('configWXDeviceWiFi', {}, function(res){
      if( res.err_msg == "configWXDeviceWiFi:ok")
      {
        alert("Ok!");
      }
      else if( res.err_msg == "configWXDeviceWiFi:cancel")
      {
        alert("Cancle!");
      }
      else
      {
        alert("res.err_msg : " + res.err_msg + "\n\nres.desc : " + res.desc);
      }
    });
  };
});

wx.error(function (res) {
  alert(res.errMsg);
});