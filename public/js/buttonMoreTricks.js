

$(function () {
    $("div.tricks").slice(6).hide();
    $("#loadLessTrick").hide("slow");
    $("#loadMoreTrick").click(function(e){
        e.preventDefault();
        $("div.tricks:hidden").slice(0, 6).slideDown();
        if ($("div.tricks:hidden").length === 0) {
            $("#loadMoreTrick").hide("slow");
            $("#loadLessTrick").show("slow");
        }
    });
    $("#loadLessTrick").click(function(e){
        e.preventDefault();
        $("div.tricks").slice(6, $("div.tricks").length).hide();
        $("#loadLessTrick").hide("slow");
        $("#loadMoreTrick").show("slow");

    });
});
console.log($("div.tricks").slice(0, 6));