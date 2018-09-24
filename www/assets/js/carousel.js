$(function () {
 function random(min,max,l)
{var arr = [],m = [],n = 0;
  if (max - min < l-1) return;
  for (var i=0; i<=(max-min); i++)m[i] = i + min;
  for (var i=0; i<l; i++) {n = Math.floor(Math.random()*(m.length)); arr[i]=m.splice(n,1)[0];};
  return arr
}
 
 
    var arr = random(0,36,37), carusel = $('#carusel');
    function rand(min, max, integer) {
      var r = Math.random() * (max - min) + min;
      return integer ? r|0 : r;
    }
 
    $.each(arr,function(indx, el){
          $('<li/>',{text : el, data : {i : el},css : {'background-color' : el ? el%2 ? '#ff513f' : '#1b1d2b' : '#86b817'},color : el ? 'white' : 'black'}).appendTo(carusel)
          carusel.width('+=60')
          });
   function lotto()
    {
    var n = rand(0 ,arr.length, true);
 
        r = rand(2 ,5, true);
 
        carusel.stop();
    (function go() {
        carusel.animate({
            left: '-=50',
        },50,   
      function () {
            var li = $('li:first');
                data = $('#carusel>li:eq(5)').data('i');
            if (data == n) r--;
 
 
            li.appendTo(carusel)
            carusel.css({
                left: '0px'
            });
            r && go();
        })
    }())
    
    }
    $('[type="button"]').on({click : lotto})
 
})
