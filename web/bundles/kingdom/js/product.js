var timer = null;

$(function(){
    Product.page = 0;
    Product.initBlock();
        

    $(window).resize(function() {
        if (timer !== false) {
            clearTimeout(timer);
        }
        timer = setTimeout(function() {
            console.log('resized');
            Product.initBlock();
//            Product.resizeSlide();
        }, 200);
    });
    $(window).bottom({proximity: 0.05});
    $(window).on("bottom",function(){
        if(!Product.isLoading){
            Product.isLoading = true;
            Product.addPage();    
        }
    });
    
   if(quick != null){
       Detail.showPopup(quick);
   }
    $("#productHead").flexslider({animation : "fade", controlNav : true});
        
});   



var Product = {
    page : 0, 
    timer : false,
    isLoading : false,
    initBlock : function(){
       var numOfCol = Math.floor($("html").width()/225);    
       if(numOfCol < 3) numOfCol = 3;
       if(numOfCol > 6) numOfCol = 6;                
       $("#itemList").css({width : numOfCol *225}).BlocksIt({
           numOfCol: numOfCol,
           offsetX: 10,
           offsetY: 10,
           blockElement: '.itemBox'
       });                  
     },
     
    resizeSlide : function (){
        var newheight = Math.floor(($("html").width()/1920)*350);
        $(".headImg").css({height : newheight+"px"})        
    },
    
    addPage : function(){
        $("#indicator").show();
        Product.page++;        
       $.ajax({
           type : "GET",
           url : addUrl+"?page="+Product.page,
           dataType : "JSON",
       }).success(function(data){
           Product.drawProducts(data).done(function(){
               $("#indicator").hide();
                Product.isLoading = false;
           });
       });
               
    },
    
    drawProducts : function(data){
        var def = new $.Deferred();
        if(data == null){
          def.resolve();
          return def.promise();
        }
        console.log(data);
        for(var i = 0; i < data.length ; i++){
            var $pBox = $("<div>").attr({"id" : "product"+data[i].id}).addClass("itemBox added");
            var $strBox = $("<div>").addClass("strbox");
            var $strName = $("<span>").addClass("strboxName").append(data[i].name);
            var $strAuthor = $("<span>").addClass("strboxAuthorName").append(data[i].nick);
            $strBox.append($strName).append("<br>").append($strAuthor);
            var iKeys = Object.keys(data[i].items);
            for(var j = 0; j < data[i].frame ; j++){
                if(j === iKeys.length) break;
                var $iBox = $("<div>")
//                    .attr({"id" : "item"+data[i].items[iKeys[j]].id})
                    .addClass("thumbBox")
                    .css("background-image" ,"url("+thumbUrl+Common.convert01(data[i].authorId, 5)+"/"+data[i].num+"/"+iKeys[j]+"/thumb.jpg)")
                    .attr({"onClick" : "Detail.showPopup("+data[i].items[iKeys[j]].id+")"});
                $pBox.append($iBox);                
            }
            $pBox.append($strBox);
            $("#itemList").append($pBox);
        }
        Product.initBlock();
        $(".added").removeClass("added");
        def.resolve();
        return def.promise();
    }
};  