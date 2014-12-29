 
 
 
 $(document).ready(function(){
if(document.getElementById('spinner')){
    var counter = 0;

setInterval(function() {
    var frames=4; var frameWidth = 47 ;
    var offset=counter * -frameWidth;
    document.getElementById("spinner").style.backgroundPosition=
        offset + "px" + " " + 0 + "px";
    counter++; if (counter>=frames) counter =0;
}, 70);
} 
if(document.getElementsByClassName('spinner')){ 
   var counter1= 0;
setInterval(function() {
 
    var frames1=4; var frameWidth1 = 47 ;
    var offset1=counter1 * -frameWidth1;
 $(".spinner").css("backgroundPosition" ,offset1 + "px" + " " + 0 + "px")
   
    counter1++; if (counter1>=frames1) counter1 =0;
}, 70);
}
 
});

