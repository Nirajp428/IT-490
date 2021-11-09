$("#add").click( function() {
    var value = parseInt($(".count").text(), 10) + 1;
    $(".count").text(value);    
});

$("#remove").click( function() {
    var value = parseInt($(".dislike").text(), 10) + 1;
    $(".dislike").text(value);    
});
