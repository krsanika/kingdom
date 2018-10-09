$(function(){
    Login.intro();        
});

var Login = {
    $box : null, 
    $screen : null,     
    $refer : null,
    $blackScr : null,
    boxHeight : 0,
    isSending : false,
   
    popUp : function (){
        
        Login.$box = $("#login");
        Login.$screen = $("#loginScreen");
        Login.boxHeight = Math.floor($(window).height()/2 - Login.$box.height()/2);
        Login.$screen.show();        
        Login.$box.show().animate({top : Login.boxHeight+"px"}, 1000, "easeOutElastic");
    },
    
    popDown : function (hide){
        Login.boxHeight = 0;
        Login.$screen.hide();
        $("#"+hide).hide().animate({top : "-260px"}, 1000, "easeOutElastic");
    },
    
    switch : function(hide, show){
        $("#"+hide).hide().animate({top : "-260px"}, 1000, "easeOutElastic");
        $("#"+show).show().animate({top : Login.boxHeight+"px"}, 1000, "easeOutElastic");
    },
    
    sendForgot : function(){
        if(!Login.isSending){
            Login.isSending = true;                   
            $.ajax({
                type : "POST",
                url : forgotUrl,
                data : { "username" : $("#emailInput").val()},
                dataType : "JSON",
            }).success(function(data){
                console.log(data);
                Login.isSending = false;
                if(data.status){
                    $("#forgotForm").remove();
                    $("#forgotAjax").empty().text(data.msg).css({marginTop : "100px"});
                    $("#forgotSubmit").remove();
                }else{
                    $("#forgotAjax").empty().text(data.msg);
                }
            });            
        }
    },
    
    sendNew : function(){
        if(!Login.isSending){
            $("#newoneAjax").text(" ");
            Login.isSending = true;       
            var username = $("#newUsername").val();
            var password =$("#newPass").val();
            var repass =$("#newRepass").val();
            if(repass != password){
                $("#newoneAjax").text("패스워드가 일치하지 않습니다.");
                Login.isSending = false;
            }else{                
                $.ajax({
                    type : "POST",
                    url : newUrl,
                    data : { "username" : username, "password" : password, "nick" : username},
                    dataType : "JSON",
                }).success(function(data){
                    console.log(data);
                    Login.isSending = false;
                    if(data.status){
                        $("#newoneForm").remove();
                        $("#newoneAjax").empty().text(data.msg).css({marginTop : "100px"});
                        $("#newoneSubmit").remove();
                    }else{
                        $("#newoneAjax").empty().text(data.msg);
                    }
                });                
            }
            
        }
        
    },
    
    intro : function(){
        $refer = $("#refer")
        $blackScr = $("#blackScreen");
        $("body").addClass("noscroll scrollItems");
        $refer.on("click", function(){
            Login.startAnim();
            Login.popUp();
        });
        var queue = new createjs.LoadQueue();
        queue.addEventListener("fileload", function(){
            if(error){
                Login.$box = $("#login");
                Login.$screen = $("#loginScreen");
                $blackScr.remove();
                $refer.remove();
                Login.boxHeight = Math.floor($(window).height()/2 - Login.$box.height()/2);
                Login.$screen.show();
                Login.switch("login", "email");
                $("#itemList").animate({top : "- "+($("#itemList").height()-$(window).height())+"px"}, 100000, "linear");
                $("#emailAjax").text("메일주소나 비밀번호가 다릅니다!")
            }else{
                $refer.css({backgroundImage : "url("+refPath+")", top : "60px", height : $(window).height()-60 +"px"});
                $blackScr.animate({opacity : 0}, 2000, "linear", function(){
                    $blackScr.remove()   
                });
            }

        });
       queue.loadFile({id : "ref", src : refPath});       
    },
    
    startAnim : function(){
        $refer = $("#refer")
        $blackScr = $("#blackScreen");        
        $("#itemList").animate({top : "- "+($("#itemList").height()-$(window).height())+"px"}, 100000, "linear");
        $refer.animate({opacity : 0}, 800, "easeInCirc", function(){
            $refer.remove();
        });        
        if(Login.boxHeight == 0){
            Login.popUp();
        }
    }
    
};