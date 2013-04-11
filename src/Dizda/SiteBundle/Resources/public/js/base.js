$(function() {

    $('img', '.photos').lazyload();

    $('.photos', '.thumbnail').on('mousemove', function(e) {
        var elem = $(this);
        var x    = e.pageX - elem.offset().left;

        var photos   = elem.find('img');
        var nbPhotos = photos.length;
        var sizeDiv  = elem.width();

        var coord = parseInt((x * nbPhotos) / sizeDiv);
        //console.log(coord, photos, nbPhotos, sizeDiv);

        photos.hide();
        elem.find('img:eq('+coord+')').show();

        /*var coord = parseInt((e.pageX * nbPhotos) / 1 / sizeDiv);
        $('p').text(coord);

        $('div img').hide();
        $('div img:eq('+coord+')').show();*/
    });

});