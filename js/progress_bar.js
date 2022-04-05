$(window).load(function(){
console.log("doc ready");
  jQuery('.js-loading-bar').modal({
    backdrop: 'static',
    show: false
  });

  var $modal = jQuery('.js-loading-bar');
console.log("in js");
    $bar = $modal.find('.progress');

console.log("bar modal");
console.log ($bar);
    $modal.modal('show');
$bar.animate({
    width: "70%"
}, 2500);
    $bar.addClass('animate');
    setTimeout(function() {
console.log("in setTimeout");
      $bar.removeClass('animate');
      $modal.modal('hide');
    }, 7000);
})
