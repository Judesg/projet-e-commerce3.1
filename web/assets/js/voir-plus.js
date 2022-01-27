$("p#content-comment").each(function(i){
        // on récupère la longueur du texte et on coupe à la longueur 100 (s'il est aussi long)
        var contenu = $(this).html();
        var longueur = contenu.length;
        if (longueur > 100){
            var debut = contenu.substr(0,100);
            var suite = contenu.substr(101);
            $(this).html(debut);
            $(this).append("<span style='font-weight: bold' id='continuer"+(i+1)+"'> [Afficher la suite...]</span>");
            $(this).append("<span id='suite"+(i+1)+"'>"+suite+"</span>");
            $(this).append("<span style='font-weight: bold' id='masquer"+(i+1)+"'> [Réduire]</span>");
            $("#suite"+(i+1)).hide();
            $(this).find("span#continuer"+(i+1)).click(
            function(){
                $(this).hide();
                $("#suite"+(i+1)).fadeIn("slow");
                $("#masquer"+(i+1)).fadeIn("slow");
            })
            $(this).find("span#masquer"+(i+1)).click(
            function(){
                $(this).hide();
                $("#suite"+(i+1)).fadeOut("slow",function(){
                $("#continuer"+(i+1)).fadeIn("slow")});
            }).hide()
        }
    });