$(function(){
//    Detail.drawPopup();
});

var Detail = {
    $detailBox : $("#detailBox"),
    $flag : $("#detailFlag"),
    $screen : $(".screen"),
    $value : $("#value"),
    $authorBox : $("#authorBox"),
    $authorImage : $("#authorImage"),
    $createrAddress : $("#createrAddress"),    
    $tagNum : $("#tagNum"),    
    $otherGoods : $("#otherGoods"),
    $authorGoods : $("#authorGoods"),
    $phone : $("#phone"),
    $tablet : $("#tablet"),
    $slideArea : $("#slide"),

    sub : 0,
//    scroll : 0,
//    y : 0,
    itemId : 0,
    category : 0,
    
/*    
    showPopup : function($id){
        Detail.getAjax($id).done(function(){
            Detail.$screen.show();
            Detail.$detailBox.show();
            Detail.$btnClose.show();
        });

    },

    hidePopup : function(){
            Detail.$detailBox.hide(500);
            Detail.$screen.hide();
            $("body").removeClass("noscroll").off("mousewheel");
            $(window).scrollTop(Detail.scroll);
    },

    getAjax : function($id){
       var data = null;
       var def = new $.Deferred();
       $.ajax({
           type : "GET",
           url : detailUrl+"/"+$id,
           dataType : "JSON",
           data : data           
       }).success(function(data){
           Detail.DrawPopup(data).done(function(){                
               def.resolve();
           });
       });
       
        return def.promise();
    },
*/    
    
    
/*    drawPopup : function(){
       Detail.itemId = data.id;
       Detail.category = data.category;
       var $slider = $("<div>").attr({id : "#slider"}).addClass("flexslider").append($("<ul>").addClass("slides"));
       $("#image").empty().append($slider);
       
       var def = new $.Deferred();
       var tag = Common.convert01(data.productId.authorId.id, 5)+data.productId.num+data.category;
//       var authorImgUrl = "url('http://scraping.pro/res/vwr_proxy/anonymous.jpg')";
        console.log(item);
//        Detail.$name.text(data.productId.name);
//        Detail.$descript.html(data.descript);
//        Detail.$category.text(Common.cateToStr(data.category));
        
        Detail.$value.text("????원");
//        Detail.$color.text(data.color);
//        Detail.$authorName.text(data.productId.authorId.name);        
//        Detail.$authorDescript.append(data.productId.authorId.descript);
//        Detail.$authorMore.click(function(){
//          location.href=mypage+"?id="+data.productId.authorId.userId.id; 
//        });
        Detail.$authorBox.addClass("Bg"+data.productId.genre);       
//        Detail.$createrName.text(data.createrId.name);
        Detail.$createrAddress.text(data.createrId.address);
//        Detail.$createrDelPrice.text(data.createrId.delprice);        
        Detail.$tagNum.text(tag);        
        var flagUrl = Detail.$flag.css("background-image");        
        var newUrl = flagUrl.slice(0, -7)+Common.convert01(data.productId.authorId.region, 2)+".png)";
        Detail.$flag.css({backgroundImage : newUrl});
        if(data.authorImage !== null){
            authorImgUrl = "url("+data.authorImage+")";
        }else if(data.productId.authorId.userId.imgPath !== null){
            authorImgUrl = "url("+data.productId.authorId.image.path+")";
        }        
        Detail.$authorImage.css({backgroundImage : authorImgUrl});
//        var $carouselUl = $("#carousel > ul");
        for(var i = 0; i <data.allImgPath.length; i++){            
            var $image = $("<img>").attr("src", data.allImgPath[i]);
            var $li = $("<li>").attr("data-thumb", data.allImgPath[i]).append($image);
            $("#slider > .slides").append($li);
//            $carouselUl.append($li);
        }
        
          $('.flexslider').flexslider({
            animation: "fade",
//            controlNav: "thumbnails",
            slideshow : true,
            itemWidth : "635px",
            itemMargin : "0px"
        });
        $(".flexslider .flex-viewport .slides img").css({width : "100%"});

        if(typeof data.other.other !== "undefined"){
            var $otherTitle = $("<div>").addClass("guideText").text("이 상품의 다른 상품");
            Detail.$otherGoods.append($otherTitle);
            for(var i =0; i < data.other.other.length; i++){
                var item = data.other.other[i];
                var $otherImg = $("<div>").addClass("otherImg")
                        .css("background-image" ,"url("+thumbUrl+Common.convert01(data.authorId, 5)+"/"+data.num+"/"+data.category+"/thumb.jpg)")
                        .attr({"onClick" : "Detail.showPopup("+data.id+")"});
                var $otherText = $("<div>").addClass("otherText").text(Common.cateStr[data.category]);
                var $otherBox =  $("<div>").addClass("otherBox").append($otherImg).append($otherText);
                Detail.$otherGoods.append($otherBox);
            }
        }else{
            Detail.$otherGoods.empty();
        }
        if(typeof data.other.author !== "undefined"){
            var $authorTitle = $("<div>").addClass("guideText").text(data.productId.authorId.name+"의 다른상품");
            Detail.$authorGoods.append($authorTitle);
            for(var i =0; i < data.other.author.length; i++){
                var item = data.other.author[i];
                var $otherImg = $("<div>").addClass("otherImg")
                        .css("background-image" ,"url("+thumbUrl+Common.convert01(data.authorId, 5)+"/"+data.num+"/"+data.category+"/thumb.jpg)")
                        .attr({"onClick" : "Detail.showPopup("+data.id+")"});
                var $otherText = $("<div>").addClass("otherText").text(Common.cateStr[data.category]);
                var $otherBox =  $("<div>").addClass("otherBox").append($otherImg).append($otherText);
                Detail.$authorGoods.append($otherBox);
            }
        }else{
            Detail.$authorGoods.empty();
        }
        Detail.$phone.hide();
        Detail.$tablet.hide();
        console.log(data.category);
        if(data.category == 201 || data.category == 203){
            Detail.$phone.show();
        }
        if(data.category == 202 || data.category == 204){
            Detail.$tablet.show();
        }
        
        
        
        
        Detail.scroll = $(window).scrollTop()+10;
        Detail.y = 10;
        $("body").addClass("noscroll").css({top : -Detail.scroll-30+"px"});
        Detail.$detailBox.offset({top : 10});
        Detail.$screen.offset({top : -1000});

        Detail.$detailBox.on("mousewheel", function(ev, delta, x, y){
            var top = Detail.$detailBox.offset().top;
            console.log(y, top);
//            if(top < 0) y = 0;
//            if(top > $(window).height()) y = 0;        
            Detail.$detailBox.offset(function(index, pos){
               var myObj = {};               
               myObj.top = pos.top + (y*10);
               myObj.left = pos.left;
               return myObj;
            });                
        });
        def.resolve();
        return def.promise();
    }, 
*/
    slideChange : function(value){
        $('.flexslider').flexslider({
            animation: "fade",
//            controlNav: "thumbnails",
            slideshow : true,
            itemWidth : "635px",
            itemMargin : "0px"
        });
        $(".flexslider .flex-viewport .slides img").css({width : "100%"});            
        Detail.sub = value;
        $('.flexslider').data("flexslider").flexAnimate(value-1);
    },

    setCart : function(isBuying){
//        var address = {id: Detail.addressId};
//        if(adressId==null){
//            alert("회원님의 주소를 먼저 입력해주세요.");
//        }else{
        var post = {id : Detail.id};
        if(Detail.category == "201" || Detail.category == "203"   ){
            post.subcate = $("#phoneSub").val();
            if(post.subcate ==0 ){
                alert("기종을 입력하셔야 합니다.");
                return 0;
            }
        }else if (Detail.category == "202" || Detail.category == "204"){
            post.subcate = $("#tabletSub").val();
            if(post.subcate ==0 ){
                alert("기종을 입력하셔야 합니다.");
                return 0;
            }
        }else{
            post.subcate = 0;
        }
                        
       $.ajax({           
           type : "POST",
           url : addCartUrl,
           dataType : "JSON",
           data : post,
       }).success(function(data){
           alert(data.msg);
           if(isBuying){
               var str = "";
               var count = $("#count"+data.id[i]).val();
               str += "strs["+data.id[i]+"]="+count+"&" ;
               location.href=buyInputUrl+"?id[0]="+data.cartId;
           }
       });
               
        
    }
//}
        
//    var str = "";
//    for (var i=0 ; i < ids.length; i++){        
//        var count = $("#count"+ids[i]).val();
//        str += "strs["+ids[i]+"]="+count+"&";
//    }
//    location.href=buyInputUrl+"?"+str;

};