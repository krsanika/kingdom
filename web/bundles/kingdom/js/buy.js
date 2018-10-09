
$(function(){
    Buy.$adrForm = $("#adrForm");
    Buy.$adrConfirm = $("#adrConfirm");
    Buy.$money = $("#money");
    Buy.$moneyResult = $("#moneyResult");
    Buy.$moneyConfirm = $("#moneyConfirm");
    Buy.$payMethod = $("#paymentMethod");
    Buy.$payConfirm = $("#payConfirm");
    Buy.$discount = $("#discount");
    
});

var Buy = {
    $adrForm : null,
    $adrConfirm : null,
    $discount : null,
    $money : null,
    $moneyResult : null,
    $moneyConfirm : null,
    $payMethod : null,
    $payConfirm  : null,
    server : null,
    pcode : null,
    adrStr : null,
    phone : null,
    descript : null,     
    paylast : null,
    method : null,
    methodText : null,
    cartIds : null,
    isSending : false,
    
    
    doConfirm : function(){
        Buy.server = $("#server").val();
        Buy.pcode = $("#pc1").val()+"-"+$("#pc2").val();
        Buy.adrStr = $("#addrStr").val()+" "+$("#addrStr2").val();
//        Buy.phone = $("#phone1").val()+"-"+$("#phone2").val()+"-"+$("#phone3").val();
        Buy.phone = $("#phoneNumber").val();
        Buy.descript = $("#addrDetail").val();        
        Buy.method = $("*[name=pay]:checked").val();
        Buy.methodText = $("#paystr"+Buy.method).text();        
        Buy.cartIds = new Array();
        $(".cartDetail").each(function(){
            Buy.cartIds.push($(this).attr("alt"));
        });        
        
        if(typeof Buy.method === "undefined"){
            alert("결제수단을 선택해 주세요.");
            return 0;
        }
        if(Buy.server == "" || Buy.pcode == "" || Buy.phone == "" || Buy.adrStr == ""){
            alert("배송정보가 없습니다.");
            return 0;            
        }
        
        $("#adrForm").hide();
        $("#payMethod").hide();
        $("#adrConfirm").empty().append(Buy.server).append($("<br>")).append(Buy.pcode).append($("<br>")).append(Buy.adrStr).append($("<br>")).append(Buy.phone).append($("<br>")).append(Buy.descript);
        $("#money3line").empty().text($("#discount").val());
        $("#discount").hide();
        $("#disNotice").hide();
        $("#paymentMethod").hide();
        $("#payConfirm").text(Buy.methodText);
//        var $cancelBtn = $("<div>").attr({id : "cancelBtn"}).on("click", Buy.goConfirm());
//        $(".backBtn").append($cancelBtn);
        $("#confirmBtn").attr("onclick", "Buy.goConfirm()");
    },
    
    goConfirm : function(){
        if(!Buy.isSending){
            Buy.isSending = true;
            
            var post = { 
               name : Buy.server,
               pcode : Buy.pcode,
               adrStr : Buy.adrStr,
               method : Buy.method,
               pay :  $("#moneyResult").text(),
               phone : Buy.phone,
               descript : Buy.descript,
               cartIds : Buy.cartIds,
               discount : $("#money3line").text(),
               isSame : $("#isSame").prop("checked")
            };
            console.log(post);
            $.ajax({
               type : "POST",
               url : processUrl,
               dataType : "JSON",                
               data : post
            }).success(function(data){
                if(data.result){
                    alert("결재 완료");
                    location.href = endUrl;
                }else{
                    alert("결재에 실패했습니다.");
                }
            });
            
        }else{
            alert("결재중입니다.");
        }
        
        
    },
    
    goCancel : function(){
    },
    
    displayCount : function(){
        var point = parseInt($("#discount").attr("alt"));
        var use = parseInt($("#discount").val());
        var pay = parseInt(Buy.$moneyResult.attr("alt"));
        if(typeof use != "number"){
            use = 0;
        }
        if(point - use < 0 || pay - use < 0){
            alert("이 이상 할인할 수 없습니다.");
            return 0;
        }                        
        Buy.$moneyResult.text(pay-use);
    },
    
    sameAdr : function(){        
        var nick = $("#usernick").text();
        var codes = $("#usercodes").text();
        var pc1 = codes.slice(0,3);
        var pc2 = codes.slice(4,7);
        var text = $("#usertext").text();
        var phone = $("#userphone").text();
//        var phoneNumber = phone
//        var phone1 = phone.slice(0,3);
//        var phone2 = phone.slice(4,8);
//        var phone3 = phone.slice(9,14);
        $("#server").val(nick);
        $("#pc1").val(pc1);
        $("#pc2").val(pc2);
        $("#addrStr").val(text);
        $("#phoneNumber").val(phone);
//        $("#phone1").val(phone1);
//        $("#phone2").val(phone2);
//        $("#phone3").val(phone3);
                
    }
    
    
};

