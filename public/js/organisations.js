function getOrganisations(a){$.getJSON("admin/ajax/organisations/find?q="+a,function(a){$(".blocklink-wrapper").empty(),a.length>0?$("#trust-selected").show():$("#trust-selected").hide(),$.each(a,function(a,b){null==b.thumb&&(b.thumb="img/organisation.png"),$("main h1").text("Organisations"),$(".blocklink-wrapper").append('<div class="col-md-2 blocklink"><div class="check"><input id="org-'+b.id+'" type="checkbox" name="trusted[]" value="'+b.id+'"><label for="org-'+b.id+'"></label></div><a href="organisations/'+b.id+'"><div class="profile-img"><img src="'+b.thumb+'" alt="'+b.name+'"></div><p>'+b.name+"</p></a></div>")})})}$(function(){$("#q").keydown(function(){$(this).val().length&&($(".blocklink-wrapper").html('<div class="col-md-12 text-center"><i class="fa fa-circle-o-notch fa-spin"></i></div>'),getOrganisations($(this).val()))})});