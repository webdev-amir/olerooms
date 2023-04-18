
/* end dot nav */

(function($) {

  /**
    * Originally written by Sam Sehnert
   */

  $.fn.visible = function(partial) {
      var $t            = $(this),
          $w            = $(window),
          viewTop       = $w.scrollTop(),
          viewBottom    = viewTop + $w.height(),
          _top          = $t.offset().top,
          _bottom       = _top + $t.height(),
          compareTop    = partial === true ? _bottom : _top,
          compareBottom = partial === true ? _top : _bottom;
    return ((compareTop >= viewTop) && (compareBottom <= viewBottom));
  };
})(jQuery);


jQuery(window).scroll(function(event) {
/*Comman all Page*/
jQuery("section").each(function(i, el) {
 var el = jQuery(el);
    if (el.visible(true)) {
      el.addClass("selected"); 
    } 
 else {
      //el.removeClass("selected"); 
    } 
  });  
});
