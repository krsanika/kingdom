
var naverApi = {
    //ログインポップアップ
    naverLogin : function(){        
        location.href = "https://nid.naver.com/oauth2.0/authorize?client_id="+naverAppId+"&response_type=code&redirect_uri=http%3A%2F%2F218.54.47.42%3A8081%2Fapp_dev.php%2Flogin%2Fnaver&state="+naverState;
    },

};






