var adrChange = false;

var $collectionHolder;
var $addFileLink;
var $newLinkLi;

var $readTitle;
var $readDate;
var $readContent;
var $readOrderId;
var $readImages;
var nowReading = false;

$(function(){
    var height =$(".mainContent").height();
    if(height < $(window).height()) height = $(window).height()+75;
    $(".subMenu").height(height);
    if(action === "index"){
        $("#myInfo").css({color : "#343434", fontWeight : "bold"});
    }else if(action ==="cart" || action ==="buyInput"){
        $("#myCart").css({color : "#343434", fontWeight : "bold"});
    }else if(action ==="payment" || action ==="ordered" || action ==="buyEnd"){
        $("#myPay").css({color : "#343434", fontWeight : "bold"});
    }
    
    // Get the ul that holds the collection of tags
    $collectionHolder = $('#fileForm');
    
//    $newLinkLi = $('#addImages').after($addFileLink);

    // add the "add a tag" anchor and li to the tags ul
//    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

//    $addFileLink.on('click', function(e) {
//        // prevent the link from creating a "#" on the URL
//        e.preventDefault();
//        // add a new tag form (see next code block)
//        addFileForm($collectionHolder, $newLinkLi);
//    });
    $("#qna_송신하기").addClass("cmnSubmit");    
    
    $readTitle = $("#readTitle");
    $readDate = $("#readDate");
    $readContent = $("#readContent");
    $readOrderId = $("#readOrderId");
    $readImages = $("#readImages");
   
});

function mainAdr(id){
    if(!adrChange){
        adrChange =  true;
        $("input").attr({disabled : "disabled"});
        location.href = '{{url("mypage_address")}}'+"?id="+id;        
        console.log("mainAdr");
    }
};
   
function popUp(hide, show){
    var boxHeight = Math.floor($(window).height()/2 - $("#"+show).height()/2);
    if(hide !== null) $("#"+hide).hide();
    $("#"+show).show().animate({ top : boxHeight+"px"}, 1000);
};

    
function popDown (hide){    
    $("#"+hide).hide();
};
    
function addFileForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');
    
    // get the new index
    var index = $collectionHolder.data('index');
    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);
    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);
    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li>').append(newForm);        
    $collectionHolder.append($newFormLi);
//    $newLinkLi.before($newFormLi);
    $("label").remove();
}

function getQnaRead(id){
    if(!nowReading){
        nowReading = true;
        $.ajax({
            type : "GET",
            url : qnaUrl,
            data : { "id" : id},
            dataType : "JSON",
        }).success(function(data){
            console.log(data);
            $readTitle.empty().text(data.subject);
            $readContent.empty().text(data.subject);
            $readDate.empty().text(Common.timeToDate(data.date)+"발송됨");
            $readOrderId.empty().text(data.orderId);
            $readImages.empty();            
                for(var i = 0; i < data.files.length; i++){
                    var $a = $("<a>").attr({href : "/pristo/web/bundles/kingdom/image"+data.files[i].path});
                    var $name = $("<span>").addClass("size12 blue").text(data.files[i].name);
                    $a.append($name).appendTo($readImages);
                }
            nowReading = false;
            popUp(null, "readQna");
    });
      
        
    }else{
        alert("QnA를 읽고 있습니다.");
    };
    
    function drawTotalPoint(){
        
    };
    

};

