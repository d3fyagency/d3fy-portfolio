$(window).load(function(){
  var $grid = $('.grid').isotope({
    itemSelector: '.d3fy-item',
    transitionDuration: '0.6s',
    masonry: {
      columnWidth: 0
    }
  });
  $('.portfolio-filters-inline').on('click', 'a', function() {
    $('.portfolio-filters-inline a').removeClass("current");

    //adds the class to whichever item you clicked
    $(this).addClass("current");
    var filterValue = $(this).attr('data-filter');
    $grid.isotope({
      filter: filterValue
    });
  });

});
