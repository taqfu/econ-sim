var url = "http://sim.taqfu.com";

$('body').on('click', '.docks', function() {
});


function refresh_map (avatarID){
    $.get(url + "/a/" + avatarID + "/map", function (data){
        $("#map").html(data);
    });
  }
function refresh_clock(avatarID){
  $.get(url + "/a/" + avatarID + "/clock", function (data){
      $("#time").html(data);
  });
}
