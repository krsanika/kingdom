var Address = {        
    // 우편번호 찾기 iframe을 넣을 element
    search : document.getElementById('searchWrap'),

    viewAddForm : function(){                
        if($("#addForm").css("display") == "none"){
            $("#addForm").show(500);            
        }else{
            $("#addForm").hide(500);
        }
        
        
    },

    closeDaumPostcode : function () {
        // iframe을 넣은 element를 안보이게 한다.
        $("#searchWrap").hide(500);
        
    },

    openDaumPostcode : function () {
        new daum.Postcode({
            oncomplete: function(data) {
                // 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.
                // 우편번호와 주소 및 영문주소 정보를 해당 필드에 넣는다.
                $('#pc1').val(data.postcode1);
                $('#pc2').val(data.postcode2);
                $('#addrStr').val(data.address);
//                $('#addrEnglish').val(data.addressEnglish);
                // iframe을 넣은 element를 안보이게 한다.
                $("#searchWrap").hide(500);
                document.getElementById('addrStr2').focus();
            },
            width : '100%',
            height : '100%'
        }).open();          
    },
    
}

