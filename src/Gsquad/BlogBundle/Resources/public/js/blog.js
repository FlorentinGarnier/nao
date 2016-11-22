/**
 * Created by aigie on 20/11/2016.
 */

$("#addComment").click(function(){
    var el = $("#comment-form")[0];
    el.classList.remove("hide");
    el.classList.add("show");

    $("#addComment")[0].classList.add("hide");
});
