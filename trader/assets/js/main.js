$(document).on('click','#sidebar li',function(){
    $(this).addClass('active').siblings().removeClass('active');
});

// left munu sidebar dp toggle 
// $('.sub-menu ul').hide();
// $(".sub-menu a").click(function(){
//     $(this).parent(".sub-menu").children("ul").slideToggle("100");
//     $(this).find(".right").toggleClass("fa-caret-up fa-caret-down");
// });

function show_hide() {
    var click = document.getElementById("list");
    if(click.style.display ==="none") {
       click.style.display ="block";
    } else {
       click.style.display ="none";
    } 
 }

//  sidebar toggle
$(document).ready(function(){
    $("#toogleSidebar").click(function(){
        $(".left-menu").toggleClass("hide");
        $(".content-wrapper").toggleClass("hide");
    });
});

$(document).ready( function () {
    $('#example').DataTable();
} );
