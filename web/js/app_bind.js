wx.ready(function () {  
  document.querySelector('#a_bind_confirm').onclick = function () {
    WeixinJSBridge.invoke('closeWindow', {}, function(res){
    });
  };
});

wx.error(function (res) {
  alert(res.errMsg);
});