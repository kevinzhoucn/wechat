<script>
  wx.config({
      debug: false,
      appId: '{{ wechat.appid }}',
      timestamp: {{ wechat.timestamp }},
      nonceStr: '{{ wechat.nonceStr }}',
      signature: '{{ wechat.signature }}',
      jsApiList: [
        'configWXDeviceWiFi',
        'closeWindow'
      ]
  });
</script>