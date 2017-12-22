var url = "http://sim.taqfu.com";

$('body').on('click', '.docks', function() {
});
function load_map(x, y){
  $.get(url + "/map/" + x + "/" + y, function (data){
      $("#map").html(data);
  });
}

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
