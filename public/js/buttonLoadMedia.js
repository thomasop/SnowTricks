
$("#hideMedia").hide();
if (window.innerWidth < 570){
    $(function (){
        $("#loadMedia").click(function(e){
            e.preventDefault();
            $("section.omg").removeClass("load-media");
            $("#loadMedia").hide();
            $("#hideMedia").show();
        });
        $("#hideMedia").click(function(e){
            e.preventDefault();
            $("section.omg").addClass("load-media");
            $("#hideMedia").hide();
            $("#loadMedia").show();
        });
    });
}