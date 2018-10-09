$(function(){
    Common.switchMyBox();
    var timer = false;
    $(window).resize(function() {
        if (timer !== false) {
            clearTimeout(timer);
        }
        timer = setTimeout(function() {
            Common.switchMyBox();
        }, 200);
    });    
    
});   
    

var Common = {
    cateStr : {
        101 : "Wall Print",
        102 : "Wall Frame",
        103 : "Wall Canvas",
        201 : "Phone Case",
        202 : "Tablet Case",
        203 : "Phone Skin",
        204 : "Tablet Skin",        
        205 : "Laptop Skin",
        301 : "Mug Cup",
        302 : "Rug",
        303 : "Pillow",
        304 : "Clock",
        401 : "Cushion",
        402 : "Bag",        
    },
    phoneStr : {
        1 : "Apple iPhone 5",
        2 : "Apple iPhone 5s/5c",
        3 : "Apple iPhone 6",
        4 : "Apple iPhone 6+",
        5 : "Samsung Galaxy 3/3S",
        6 : "Samsung Galaxy Note 3",
        7 : "Samsung Galaxy Note 2",
        8 : "LG G3 Screen",
        9 : "LG AKA"
    },
    tabletStr : {
        1 : "Apple iPad 2",        
        2 : "Apple iPad 3",
        3 : "Asus MeMO Pad",
        4 : "Asus VivoTab",
        5 : "Samsung Galaxy Tab 4",
        6 : "Samsung Galaxy Tab 3",                
    },
    
    nl2br : function (str, is_xhtml){
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');        
    },
    
    convert01 : function(int, d){
        var str = "" + int;
        var pad = "";
        for(var i=0; i < d ; i++){
            pad += "0";
        }

        return pad.substring(0, pad.length - str.length) + str;
    },
    
    switchMyBox : function (){
        var width = $("html").width();
        if(width > 950){
            $("#userBox").removeClass("under950").addClass("over950");
        }else{
            $("#userBox").removeClass("over950").addClass("under950");        
        }
    },
    
    cateToStr : function(category){
        return this.cateStr[category];
    },
    
    timeToDate : function(time){
        //Dateオブジェクトを利用
        var d = new Date(time);
        var year  = d.getFullYear();
        var month = d.getMonth() + 1;
        var day   = d.getDate();
        var hour  = ( d.getHours()   < 10 ) ? '0' + d.getHours()   : d.getHours();
        var min   = ( d.getMinutes() < 10 ) ? '0' + d.getMinutes() : d.getMinutes();
        var sec   = ( d.getSeconds() < 10 ) ? '0' + d.getSeconds() : d.getSeconds();
        return year + '-' + month + '-' + day + ' ' + hour + ':' + min + ':' + sec;
    }
    
    
}