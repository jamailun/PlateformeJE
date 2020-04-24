// cache selectors
/*var lastId,
navbar = $(".nav-bar"),
navbarHeight = navbar.outerHeight(),
// all list items
navbarItems = navbar.find("a"),
// anchors corresponding to menu items
scrollItems = navbarItems.map(function() {
  var item = $(this).attr("href");
  if (item.length) {
    return item;
  }
});

// bind click handler to menu items so we can get a fancy scroll animation
navbarItems.click(function(e) {
	var target = this.hash;
	var $target = $(target);
	$('html, body').stop().animate({
			'scrollTop': $target.offset().top - navbarHeight - 15
	}, 1000, 'swing', function () {
			window.location.hash = target;
	});
	e.preventDefault();
});

// bind to scroll
$(window).scroll(function() {
   // get container scroll position
   var fromTop = $(this).scrollTop() + navbarHeight + 65;
   // get id of current scroll item
   var cur = scrollItems.map(function() {
     if ($(this).offset().top < fromTop) {
      return this;
     }
   });
   // get the id of the current element
   cur = cur[cur.length - 1];
   var id = cur && cur.length ? cur[0].id : "";
   if (lastId !== id) {
       lastId = id;
       // set/remove active class
       navbarItems.parent().removeClass("active").end().filter("[href='#" + id + "']").parent().addClass("active");
   }
});*/

