
$("#hideMedia").hide();
if (window.innerWidth < 770){
    $(function (){
        $("#loadMedia").click(function(e){
            e.preventDefault();
            $("div.omg").removeClass("load-media");
            $("#loadMedia").hide();
            $("#hideMedia").show();
        });
        $("#hideMedia").click(function(e){
            e.preventDefault();
            $("div.omg").addClass("load-media");
            $("#hideMedia").hide();
            $("#loadMedia").show();
        });
    });
}