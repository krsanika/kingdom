$(function(){
    $(".needId").click(function(){
        Title.backPage();
    });
 });

var Title = {
    backPage : function(){
        if( userId == null){
            location.href= titleUrl;
            alert("로그인이 필요한 서비스 입니다.");
        }
    }
};