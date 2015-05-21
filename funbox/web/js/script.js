$( document ).ready(function() {

    var defaultClass = 'shelf';
    $(".tag").hover(function(e) {
        
        switch(e['target']['parentElement']['className']){
            case defaultClass+' default':
                $(this).parent().attr('class', defaultClass+' defaultHover');
            break;
            case defaultClass+' selected':
                $(this).parent().attr('class', defaultClass+' selectedHover');
                $(this).find('h4.text').html($(this).find('h4.selectedHover').html());
            break;
        }

    }, function(e){

        $(this).find('h4.text').html($(this).find('h4.default').html());
        
    	switch(e['target']['parentElement']['className']){
            case defaultClass+' defaultHover':
                $(this).parent().attr('class', defaultClass+' default');
            break;
            case defaultClass+' selectedHover':
                $(this).parent().attr('class', defaultClass+' selected');
            break;
        }

    });

    // Click on tag
    $(".tag").click(function(e) {
    	
        switch(e['target']['parentElement']['className']){
            case defaultClass+' default':
                $(this).parent().attr('class', defaultClass+' selected');
                $(this).parent().find('p.text').html($(this).parent().find('p.selected').html());
            break;
            case defaultClass+' defaultHover':
                $(this).parent().attr('class', defaultClass+' selected');
                $(this).parent().find('p.text').html($(this).parent().find('p.selected').html());
            break;
            case defaultClass+' selected':
                $(this).parent().attr('class', defaultClass+' default');
                $(this).parent().find('p.text').html($(this).parent().find('p.default').html());
            break;
            case defaultClass+' selectedHover':
                $(this).parent().attr('class', defaultClass+' default');
                $(this).parent().find('p.text').html($(this).parent().find('p.default').html());
            break;
        }
        
    });
    $(".clickMe").click(function(e) {
        
        switch(e['target']['parentElement']['parentElement']['className']){
            case defaultClass+' default':
                $(this).parent().parent().attr('class', defaultClass+' selected');
                $(this).parent().parent().find('p.text').html($(this).parent().parent().find('p.selected').html());
            break;
            case defaultClass+' defaultHover':
                $(this).parent().parent().attr('class', defaultClass+' selected');
                $(this).parent().parent().find('p.text').html($(this).parent().parent().find('p.selected').html());
            break;
        }
    });
 
});