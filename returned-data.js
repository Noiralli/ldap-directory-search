function searchDir(name) {
    var data = {user: name}
    $.post('../php/searchdir.php', data, function(returnedData) {
        var json = JSON.parse(returnedData);
        var counter = 0;
        for (var x=0; x<json.length; x++) {
            
            $( "#accordion2" ).append(
                "<li class='"+ifEven(counter)+"'>" +
                    "<div class='user'>"+
                        "<strong>"+json[x][0]+"</strong>"+
                        "<div class='ext'>"+ifNA(json[x][1])+"</div>"+
                        "<div class='icons'>"+
                            "<i class='fa fa-plus-circle'></i>"+
                        "</div>"+
                    "</div>"+
                    "<div class='content'>"+
                        "<strong>Location</strong>: "+json[x][2]+
                        "<span class='longspace'>&nbsp;</span> "+
                        "<strong>Email</strong>: "+json[x][3]+
                    "</div>"+
                "</li>" 
            );
            counter++;
        }
        $( "#accordion2" ).accordion({
            active: false,
            collapsible: true
        });
        $( "#searchcount" ).html(counter+" result(s)");
    });
}
