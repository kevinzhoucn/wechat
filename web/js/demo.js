!function(){
  function configWiFi() {
    wx.invoke('configWXDeviceWiFi', {}, function(res){
      if(res.err_msg == 'configWXDeviceWiFi:ok'){
        alert('配置成功!');
      } else {
        alert('配置失败！请重试');
      }
    });
  }

  wx.ready(function(){
    $('#startWifi').click(function(){
      // alert("hello");
      configWiFi();
    });
  });
}();