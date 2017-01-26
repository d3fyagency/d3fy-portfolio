jQuery(window).load(function(){
  var $grid = jQuery('.grid').isotope({
    itemSelector: '.d3fy-item',
    transitionDuration: '0.6s',
    masonry: {
      columnWidth: 0
    }
  });
  jQuery('.portfolio-filters-inline').on('click', 'a', function() {
    jQuery('.portfolio-filters-inline a').removeClass("current");

    //adds the class to whichever item you clicked
    jQuery(this).addClass("current");
    var filterValue = jQuery(this).attr('data-filter');
    $grid.isotope({
      filter: filterValue
    });
  });
});
